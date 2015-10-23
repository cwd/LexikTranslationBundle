<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Legacy\Import;

use Aspetos\Service\Exception\CemeteryAdministrationNotFoundException;
use Doctrine\ORM\EntityNotFoundException;
use Aspetos\Bundle\LegacyBundle\Model\Entity\Province;
use Aspetos\Bundle\LegacyBundle\Model\Entity\Cemetery as CemeteryLegacy;
use Aspetos\Model\Entity\Cemetery;
use Aspetos\Model\Entity\CemeteryAddress;
use Aspetos\Model\Entity\CemeteryAdministration;
use Aspetos\Service\Legacy\CemeteryService as CemeteryServiceLegacy;
use Aspetos\Service\CemeteryService;
use Aspetos\Service\CemeteryAdministrationService;
use JMS\DiExtraBundle\Annotation as DI;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CemeteryImporter
 *
 * @package Aspetos\Service\Legacy\Import
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.legacy.import.cemetery", parent="aspetos.service.legacy.import.base")
 */
class CemeteryImporter extends BaseImporter
{
    /**
     * @var CemeteryServiceLegacy
     */
    protected $legacyCemeteryService;

    /**
     * @var CemeteryService
     */
    protected $cemeteryService;

    /**
     * @var CemeteryAdministrationService
     */
    protected $administrationService;

    /**
     * @param CemeteryServiceLegacy         $cemeteryServiceLegacy
     * @param CemeteryService               $cemeteryService
     * @param PhoneNumberUtil               $phoneNumberUtil
     * @param CemeteryAdministrationService $cemeteryAdministrationService
     *
     * @DI\InjectParams({
     *     "cemeteryServiceLegacy" = @DI\Inject("aspetos.service.legacy.cemetery"),
     *     "cemeteryService" = @DI\Inject("aspetos.service.cemetery"),
     *     "phoneNumberUtil"  = @DI\Inject("libphonenumber.phone_number_util"),
     *     "cemeteryAdministrationService" = @DI\Inject("aspetos.service.cemetery.administration")
     * })
     */
    public function __construct(
        CemeteryServiceLegacy $cemeteryServiceLegacy,
        CemeteryService $cemeteryService,
        PhoneNumberUtil $phoneNumberUtil,
        CemeteryAdministrationService $cemeteryAdministrationService
    )
    {
        $this->legacyCemeteryService = $cemeteryServiceLegacy;
        $this->cemeteryService = $cemeteryService;
        $this->administrationService = $cemeteryAdministrationService;

        parent::__construct($cemeteryService->getEm(), $cemeteryServiceLegacy->getEm(), $phoneNumberUtil);
    }

    /**
     * Import!
     */
    public function run()
    {
        $this->startImport();

        $cemeteries = $this->legacyCemeteryService->findAll();
        $cemeteryCount = count($cemeteries);

        $this->writeln(sprintf('<info>%s</info> Cemeterys to import', $cemeteryCount), OutputInterface::VERBOSITY_NORMAL);

        foreach ($cemeteries as $key => $cemetery) {
            $cemeteryObject = $this->createCemetery($cemetery);
            $this->addAddress($cemetery, $cemeteryObject);

            // if there is no address, we can't create the administration (we need the region from the address)
            if ($cemeteryObject->getAddress() !== null) {
                $this->addAdministration($cemetery, $cemeteryObject);
            }

            $this->writeln(
                sprintf(
                    '%s <info>%s</info>',
                    'Created',
                    $cemeteryObject->getName()
                ),
                OutputInterface::VERBOSITY_VERY_VERBOSE
            );

            if ($key % 35 == 0) {
                $this->writeln(sprintf('<comment>Inserted %s/%s cemeteries</comment>', $key, $cemeteryCount), OutputInterface::VERBOSITY_NORMAL);
                $this->cemeteryService->flush();

                // clear objects, speeds up import
                $this->legacyEntityManager->clear();
                $this->entityManager->clear();
            }
        }

        $this->cemeteryService->flush();
        $this->stopImport();
    }

    /**
     * @param CemeteryLegacy $cemetery
     *
     * @return Cemetery
     */
    protected function createCemetery(CemeteryLegacy $cemetery)
    {
        $cemeteryObject = new Cemetery();

        $cemeteryObject
            ->setName($this->getValueOrEmpty($cemetery->getName()))
            ->setOwnerName($this->getValueOrNull($cemetery->getOwnerName()));

        $this->cemeteryService->persist($cemeteryObject);

        return $cemeteryObject;
    }

    /**
     * @param CemeteryLegacy    $cemetery
     * @param Cemetery          $cemeteryObject
     *
     * @return CemeteryAddress
     */
    protected function addAddress(CemeteryLegacy $cemetery, Cemetery $cemeteryObject)
    {
        try {
            $province = $this->findProvinceByProvince($cemetery->getProvince());
        } catch (EntityNotFoundException $e) {
            $this->writeln(sprintf('<error>ID %s: no province found - unable to continue because unable to determine region(address skipped): %s</error>', $cemetery->getCemId(), $cemetery->getProvince()), OutputInterface::VERBOSITY_NORMAL);

            return null;
        }

        $addressObject = new CemeteryAddress();
        $addressObject->setCemetery($cemeteryObject);
        $cemeteryObject->setAddress($addressObject);

        $region = $this->findRegionByProvince($province);
        $district = $this->findDistrictByName($cemetery->getDistrict());

        $addressObject
            ->setDistrict($district)
            ->setLat($this->getValueOrNull($cemetery->getGeoLat()))
            ->setLng($this->getValueOrNull($cemetery->getGeoLng()))
            ->setCountry($this->getValueOrEmpty($region->getCountry()))
            ->setStreet($this->getValueOrEmpty($cemetery->getStreet()))
            ->setZipcode($this->getValueOrEmpty($cemetery->getZip()))
            ->setCity($this->getValueOrNull($cemetery->getPlace()))
            ->setRegion($region);

        $this->cemeteryService->persist($addressObject);

        return $addressObject;
    }

    /**
     * @param CemeteryLegacy    $cemetery
     * @param Cemetery          $cemeteryObject
     *
     * @return CemeteryAdministration
     */
    protected function addAdministration(CemeteryLegacy $cemetery, Cemetery $cemeteryObject)
    {
        $administration = $this->findAministrationOrNew($cemetery);

        $administration->addCemetery($cemeteryObject);
        $cemeteryObject->setAdministration($administration);

        $region = $cemeteryObject->getAddress()->getRegion();

        $administration
            ->setName($this->getValueOrNull($cemetery->getAdministrationName()))
            ->setPhone($this->phoneNumberParser(
                $cemetery->getAdministrationPhone(),
                $region->getCountry(),
                null
            ))
            ->setCountry($this->getValueOrEmpty($region->getCountry()))
            ->setStreet($this->getValueOrEmpty($cemetery->getAdministrationStreet()))
            ->setZipcode($this->getValueOrEmpty($cemetery->getAdministrationZip()))
            ->setCity($this->getValueOrNull($cemetery->getAdministrationPlace()))
            ->setRegion($region);

        $this->cemeteryService->persist($administration);

        return $administration;
    }

    /**
     * @param string $province
     *
     * @return Province
     * @throws EntityNotFoundException
     */
    protected function findProvinceByProvince($province)
    {
        $rep = $this->legacyEntityManager->getRepository('Legacy:Province');
        $region = $rep->findOneBy(array('name' => $province));

        if ($region === null) {
            throw new EntityNotFoundException();
        }

        return $region;
    }

    /**
     * @param CemeteryLegacy $cemetery
     * @return CemeteryAdministration
     */
    protected function findAministrationOrNew(CemeteryLegacy $cemetery)
    {
        try {
            $administration = $this->administrationService->findOneByNameAndAddress(
                $cemetery->getAdministrationName(),
                $cemetery->getAdministrationStreet(),
                $cemetery->getAdministrationPlace(),
                $cemetery->getAdministrationZip()
            );
        } catch (CemeteryAdministrationNotFoundException $e) {
            $administration = new CemeteryAdministration();
        }

        return $administration;
    }
}
