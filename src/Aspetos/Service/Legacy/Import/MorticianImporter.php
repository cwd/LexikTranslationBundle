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
use Aspetos\Service\Handler\MorticianHandler;
use Aspetos\Service\Exception\MorticianNotFoundException;
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
     * @var MorticianHandler
     */
    protected $handler;

    /**
     * @param MorticianServiceLegacy $morticianServiceLegacy
     * @param MorticianService       $morticianService
     * @param MorticianHandler       $morticianHandler
     * @param PhoneNumberUtil        $phoneNumberUtil
     *
     * @DI\InjectParams({
     *     "morticianServiceLegacy" = @DI\Inject("aspetos.service.legacy.mortician"),
     *     "morticianService" = @DI\Inject("aspetos.service.mortician"),
     *     "morticianHandler" = @DI\Inject("aspetos.service.handler.mortician"),
     *     "phoneNumberUtil"  = @DI\Inject("libphonenumber.phone_number_util")
     * })
     */
    public function __construct(
        MorticianServiceLegacy $morticianServiceLegacy,
        MorticianService $morticianService,
        MorticianHandler $morticianHandler,
        PhoneNumberUtil $phoneNumberUtil)
    {
        $this->legacyMorticianService = $morticianServiceLegacy;
        $this->morticianService = $morticianService;
        $this->phoneNumberUtil = $phoneNumberUtil;
        $this->handler = $morticianHandler;

        parent::__construct($morticianService->getEm(), $morticianServiceLegacy->getEm());
    }

    /**
     * Import!
     */
    public function run()
    {
        $this->writeln('<comment>Starting import - '.date('Y-m-d H:i:s').'</comment>', OutputInterface::VERBOSITY_NORMAL);

        $morticians = $this->legacyMorticianService->findAll(60);

        $this->writeln(sprintf('<info>%s</info> Morticians to import', count($morticians)), OutputInterface::VERBOSITY_NORMAL);

        foreach ($morticians as $mortician) {
            //dump($mortician);
            $mortObject = $this->updateMortician($mortician);
            $this->addAddress($mortician, $mortObject);

            $this->writeln(
                sprintf('%s (%s) <info>%s</info>',
                    ($mortObject->getId() == null) ? 'Created' : 'Updated',
                    $mortObject->getOrigMorticianId(),
                    $mortObject->getName()
                ),
                OutputInterface::VERBOSITY_VERBOSE
            );
        }

        $this->morticianService->flush();
    }

    /**
     * @param User $mortician
     *
     * @return Mortician
     */
    protected function updateMortician(User $mortician)
    {
        $mortObject = $this->findOrNew($mortician->getUid());

        $mortObject->setCountry(strtoupper($mortician->getDomain()))
                   ->setName($this->getValueOrNull($mortician->getName()))
                   ->setCommercialRegNumber($this->getValueOrNull($mortician->getCommercialRegNumber()))
                   ->setVat($this->getValueOrNull($mortician->getVatNumber()))
                   ->setDescription($this->getValueOrNull($mortician->getDescription()))
                   ->setCommercialRegNumber($this->getValueOrNull($mortician->getCommercialRegNumber()))
                   ->setContactName($this->getValueOrNull($mortician->getContactPerson()))
                   ->setEmail($this->getValueOrNull($mortician->getEmail()))
                   ->setWebpage($this->getValueOrNull($mortician->getWww()))
                   ->setPhone($this->phoneNumberParser($mortician->getPhone(), $mortician->getDomain(), $mortician->getUid()))
                   ->setFax($this->phoneNumberParser($mortician->getFax(), $mortician->getDomain(), $mortician->getUid()))
                   ->setRegisteredAt($mortician->getRegisterDate());

        if ($mortObject->getId() == null) {
            $this->handler->create($mortObject, false);
        } else {
            $this->handler->edit($mortObject, false);
        }

        return $mortObject;
    }

    /**
     * @param int $uid
     *
     * @return Mortician
     * @throws \Aspetos\Service\Exception\MorticianNotFoundException
     */
    protected function findOrNew($uid)
    {
        try {
            $morticianObject = $this->morticianService->findByUid($uid);
        } catch (MorticianNotFoundException $e) {
            $morticianObject = new Mortician();
            $morticianObject->setOrigMorticianId($uid);
        }

        return $morticianObject;
    }

    /**
     * @param User      $mortician
     * @param Mortician $mortObject
     *
     * @return MorticianAddress
     * @throws \Cwd\GenericBundle\Exception\PersistanceException
     */
    protected function addAddress(User $mortician, Mortician $mortObject)
    {
        $addressObject = $mortObject->getAddress();

        if ($addressObject === null) {
            $addressObject = new MorticianAddress();
            $addressObject->setMortician($mortObject);
        }

        $addressObject->setLat($mortician->getGeoLat())
                      ->setLng($mortician->getGeoLng())
                      ->setCountry(strtoupper($mortician->getDomain()))
                      ->setStreet($this->getValueOrEmpty($mortician->getStreet()))
                      ->setZipcode($this->getValueOrNull($mortician->getZip()))
                      ->setCity($this->getValueOrNull($mortician->getPlace()))
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
}
