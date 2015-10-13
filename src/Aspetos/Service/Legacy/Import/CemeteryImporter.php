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

use Doctrine\ORM\EntityNotFoundException;
use Aspetos\Bundle\LegacyBundle\Model\Entity\Province;
use Aspetos\Bundle\LegacyBundle\Model\Entity\Cemetery as CemeteryLegacy;
use Aspetos\Model\Entity\Cemetery;
use Aspetos\Model\Entity\CemeteryAddress;
use Aspetos\Model\Entity\CemeteryAdministration;
use Aspetos\Model\Entity\Region;
use Aspetos\Service\Handler\CemeteryHandler;
use Aspetos\Service\Exception\CemeteryNotFoundException;
use Aspetos\Service\Legacy\CemeteryService as CemeteryServiceLegacy;
use Aspetos\Service\CemeteryService;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Psr\Log\LoggerInterface;
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
     * @var PhoneNumberUtil
     */
    protected $phoneNumberUtil;

    /**
     * @var CemeteryHandler
     */
    protected $handler;

    /**
     * @param CemeteryServiceLegacy $cemeteryServiceLegacy
     * @param CemeteryService       $cemeteryService
     * @param CemeteryHandler       $cemeteryHandler
     * @param PhoneNumberUtil        $phoneNumberUtil
     *
     * @DI\InjectParams({
     *     "cemeteryServiceLegacy" = @DI\Inject("aspetos.service.legacy.cemetery"),
     *     "cemeteryService" = @DI\Inject("aspetos.service.cemetery"),
     *     "cemeteryHandler" = @DI\Inject("aspetos.service.handler.cemetery"),
     *     "phoneNumberUtil"  = @DI\Inject("libphonenumber.phone_number_util")
     * })
     */
    public function __construct(
        CemeteryServiceLegacy $cemeteryServiceLegacy,
        CemeteryService $cemeteryService,
        CemeteryHandler $cemeteryHandler,
        PhoneNumberUtil $phoneNumberUtil
    ) {
        $this->legacyCemeteryService = $cemeteryServiceLegacy;
        $this->cemeteryService = $cemeteryService;
        $this->phoneNumberUtil = $phoneNumberUtil;
        $this->handler = $cemeteryHandler;

        parent::__construct($cemeteryService->getEm(), $cemeteryServiceLegacy->getEm());
    }

    /**
     * Import!
     */
    public function run()
    {
        $startTime = time();
        $this->writeln('<comment>Starting import - '.date('Y-m-d H:i:s').'</comment>', OutputInterface::VERBOSITY_NORMAL);

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
                OutputInterface::VERBOSITY_VERBOSE
            );

            if ($key % 35 == 0) {
                $this->writeln(sprintf('<comment>Inserted %s/%s cemeteries</comment>', $key, $cemeteryCount) , OutputInterface::VERBOSITY_NORMAL);
                $this->cemeteryService->flush();
            }
        }

        $this->cemeteryService->flush();
        $duration = time() - $startTime;
        if ($duration < 60) {
            $duration .= ' seconds';
        } else {
            $duration = round($duration / 60, 2) . ' minutes';
        }
        $this->writeln(sprintf('<comment>Ended import - %s (%s)</comment>', date('Y-m-d H:i:s'), $duration), OutputInterface::VERBOSITY_NORMAL);
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
            ->setOwnerName($this->getValueOrNull($cemetery->getOwnerName()))
        ;

        $this->handler->create($cemeteryObject, false);

        return $cemeteryObject;
    }

    /**
     * @param CemeteryLegacy    $cemetery
     * @param Cemetery          $cemeteryObject
     *
     * @return CemeteryAddress
     * @throws \Cwd\GenericBundle\Exception\PersistanceException
     */
    protected function addAddress(CemeteryLegacy $cemetery, Cemetery $cemeteryObject)
    {
        try {
            $province = $this->findProvinceByProvince($cemetery->getProvince());
        } catch (EntityNotFoundException $e) {
            $this->writeln(sprintf('<error>ID %s: no province found - unable to continue because unable to determine region(address skipped): %s</error>', $cemetery->getCemId(), $cemetery->getProvince()), OutputInterface::VERBOSITY_VERBOSE);
        }

        $addressObject = new CemeteryAddress();
        $addressObject->setCemetery($cemeteryObject);
        $cemeteryObject->setAddress($addressObject);

        $region = $this->findRegionByProvince($province);

        $addressObject
            ->setDistrict($this->getValueOrEmpty($cemetery->getDistrict()))
            ->setLat($this->getValueOrNull($cemetery->getGeoLat()))
            ->setLng($this->getValueOrNull($cemetery->getGeoLng()))
            ->setCountry($this->getValueOrEmpty($region->getCountry()))
            ->setStreet($this->getValueOrEmpty($cemetery->getStreet()))
            ->setZipcode($this->getValueOrEmpty($cemetery->getZip()))
            ->setCity($this->getValueOrNull($cemetery->getPlace()))
            ->setRegion($region)
        ;

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
        $administration = new CemeteryAdministration();
        $administration->addCemetery($cemeteryObject);
        $cemeteryObject->setAdministration($administration);

        $region = $cemeteryObject->getAddress()->getRegion();

        $administration
            ->setName($cemetery->getAdministrationName())
            ->setPhone($this->phoneNumberParser(
                $cemetery->getAdministrationPhone(),
                $region->getCountry(),
                null
            ))
            ->setCountry($this->getValueOrEmpty($region->getCountry()))
            ->setStreet($this->getValueOrEmpty($cemetery->getAdministrationStreet()))
            ->setZipcode($this->getValueOrEmpty($cemetery->getAdministrationZip()))
            ->setCity($this->getValueOrNull($cemetery->getAdministrationPlace()))
            ->setRegion($region)
        ;

        $this->cemeteryService->persist($administration);

        return $administration;
    }

    /**
     * @param string      $input
     * @param null|string $domain
     * @param null|string $uid
     *
     * @return \libphonenumber\PhoneNumber|null
     */
    protected function phoneNumberParser($input, $domain = null, $uid = null)
    {
        if (trim($input) == '') {
            return null;
        }

        try {
            return $this->phoneNumberUtil->parse($input, strtoupper($domain));
        } catch (NumberParseException $e) {
            $this->writeln(sprintf('<error>UID %s: Invalid PhoneNumber: %s</error>', $uid, $input), OutputInterface::VERBOSITY_VERBOSE);
        }

        return null;
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
}
