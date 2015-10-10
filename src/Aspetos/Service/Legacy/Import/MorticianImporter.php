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

use Aspetos\Bundle\LegacyBundle\Model\Entity\Province;
use Aspetos\Bundle\LegacyBundle\Model\Entity\User;
use Aspetos\Model\Entity\Mortician;
use Aspetos\Model\Entity\MorticianAddress;
use Aspetos\Model\Entity\Region;
use Aspetos\Service\Legacy\MorticianService as MorticianServiceLegacy;
use Aspetos\Service\MorticianService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use JMS\DiExtraBundle\Annotation as DI;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MorticianImporter
 *
 * @package Aspetos\Service\Legacy\Import
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.legacy.import.mortician", parent="aspetos.service.legacy.import.base")
 */
class MorticianImporter extends BaseImporter
{
    /**
     * @var MorticianServiceLegacy
     */
    protected $legacyMorticianService;

    /**
     * @var MorticianService
     */
    protected $morticianService;

    /**
     * @var PhoneNumberUtil
     */
    protected $phoneNumberUtil;

    /**
     * @param MorticianServiceLegacy $morticianServiceLegacy
     * @param MorticianService       $morticianService
     *
     * @DI\InjectParams({
     *     "morticianServiceLegacy" = @DI\Inject("aspetos.service.legacy.mortician"),
     *     "morticianService" = @DI\Inject("aspetos.service.mortician"),
     *     "phoneNumberUtil"  = @DI\Inject("libphonenumber.phone_number_util")
     * })
     */
    public function __construct(MorticianServiceLegacy $morticianServiceLegacy, MorticianService $morticianService, PhoneNumberUtil $phoneNumberUtil)
    {
        $this->legacyMorticianService = $morticianServiceLegacy;
        $this->morticianService = $morticianService;
        $this->phoneNumberUtil = $phoneNumberUtil;
    }

    /**
     * Import!
     */
    public function run()
    {
        $this->writeln('<comment>Starting import - '.date('Y-m-d H:i:s').'</comment>', OutputInterface::VERBOSITY_NORMAL);

        $morticians = $this->legacyMorticianService->findAll(50);

        $this->writeln(sprintf('<info>%s</info> Morticians to import', count($morticians)), OutputInterface::VERBOSITY_NORMAL);

        foreach ($morticians as $mortician) {
            //dump($mortician);
            $mortObject = $this->createMortician($mortician);
            $this->addAddress($mortician, $mortObject);
        }

        $this->morticianService->flush();
    }

    protected function createMortician(User $mortician)
    {
        $mortObject = new Mortician();
        $mortObject->setCountry(strtoupper($mortician->getDomain()))
                   ->setName($mortician->getName())
                   ->setCommercialRegNumber($mortician->getCommercialRegNumber())
                   ->setVat($mortician->getVatNumber())
                   ->setDescription($mortician->getDescription())
                   ->setCommercialRegNumber($mortician->getCommercialRegNumber())
                   ->setContactName($mortician->getContactPerson())
                   ->setEmail($mortician->getEmail())
                   ->setWebpage($mortician->getWww())
                   ->setPhone($this->phoneNumberParser($mortician->getPhone(), $mortician->getDomain(), $mortician->getUid()))
                   ->setFax($this->phoneNumberParser($mortician->getFax(), $mortician->getDomain(), $mortician->getUid()))
                   ->setOrigMorticianId($mortician->getUid())
                   ->setRegisteredAt($mortician->getRegisterDate());

        $this->morticianService->persist($mortObject);

        return $mortObject;
    }

    protected function addAddress(User $mortician, Mortician $mortObject)
    {
        $addressObject = new MorticianAddress();
        $addressObject->setMortician($mortObject)
                      ->setLat($mortician->getGeoLat())
                      ->setLng($mortician->getGeoLng())
                      ->setCountry(strtoupper($mortician->getDomain()))
                      ->setStreet($mortician->getStreet())
                      ->setZipcode($mortician->getZip())
                      ->setCity($mortician->getPlace())
                      ->setRegion($this->findRegionByProvince($mortician->getProvince()));

        $this->morticianService->persist($addressObject);

        return $addressObject;
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
     * @param Province $province
     *
     * @return Region
     * @throws \Cwd\GenericBundle\Exception\PersistanceException
     */
    public function findRegionByProvince(Province $province)
    {
        $rep = $this->morticianService->getEm()->getRepository('Model:Region');
        try {
            $region = $rep->findOneBy(array('name' => $province->getName()));
            if ($region === null) {
                throw new EntityNotFoundException();
            }
        } catch (EntityNotFoundException $e) {
            $region = new Region();
            $region->setName($province->getName())
                   ->setCountry(($province->getCountryId() == 40) ? 'AT' : 'DE');

            $this->morticianService->persist($region);
        }

        return $region;
    }
}
