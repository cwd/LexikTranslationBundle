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

use Aspetos\Bundle\LegacyBundle\Model\Entity\User;
use Aspetos\Model\Entity\Mortician;
use Aspetos\Model\Entity\MorticianAddress;
use Aspetos\Model\Entity\MorticianMedia;
use Aspetos\Model\Entity\MorticianUser;
use Aspetos\Model\Entity\Supplier;
use Aspetos\Model\Entity\SupplierAddress;
use Aspetos\Model\Entity\SupplierUser;
use Aspetos\Service\Exception\MorticianNotFoundException;
use Aspetos\Service\Exception\SupplierNotFoundException;
use Aspetos\Service\Legacy\CompanyService as CompanyServiceLegacy;
use Aspetos\Service\Legacy\MorticianService as MorticianServiceLegacy;
use Aspetos\Service\MorticianService;
use Aspetos\Service\Supplier\SupplierService;
use Cwd\GenericBundle\LegacyHelper\Utils;
use Cwd\MediaBundle\Service\MediaService;
use Doctrine\ORM\EntityNotFoundException;
use FOS\UserBundle\Model\UserManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use libphonenumber\PhoneNumberUtil;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SupplierImporter
 *
 * @package Aspetos\Service\Legacy\Import
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.legacy.import.supplier", parent="aspetos.service.legacy.import.base")
 */
class SupplierImporter extends BaseImporter
{
    /**
     * @var CompanyServiceLegacy
     */
    protected $legacyCompanyService;

    /**
     * @var SupplierService
     */
    protected $supplierService;

    /**
     * @var array
     */
    protected $relations = array();

    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var string
     */
    protected $imageUrl = 'http://www.aspetos.at/images/profile/';

    protected $mediaService;

    /**
     * @param CompanyServiceLegacy $companyServiceLegacy
     * @param SupplierService      $supplierService
     * @param PhoneNumberUtil      $phoneNumberUtil
     * @param MediaService         $mediaService
     * @param UserManagerInterface $userManager
     *
     * @DI\InjectParams({
     *     "companyServiceLegacy" = @DI\Inject("aspetos.service.legacy.company"),
     *     "supplierService" = @DI\Inject("aspetos.service.supplier.supplier"),
     *     "phoneNumberUtil"  = @DI\Inject("libphonenumber.phone_number_util"),
     *     "mediaService"     = @DI\Inject("cwd.media.service"),
     *     "userManager"      = @DI\Inject("fos_user.user_manager")
     * })
     */
    public function __construct(
        CompanyServiceLegacy $companyServiceLegacy,
        SupplierService $supplierService,
        PhoneNumberUtil $phoneNumberUtil,
        MediaService $mediaService,
        UserManagerInterface $userManager)
    {
        $this->legacyCompanyService = $companyServiceLegacy;
        $this->supplierService = $supplierService;
        $this->mediaService = $mediaService;
        $this->userManager = $userManager;

        parent::__construct($supplierService->getEm(), $companyServiceLegacy->getEm(), $phoneNumberUtil);
    }

    /**
     * @param InputInterface $input
     */
    public function run(InputInterface $input)
    {
        $this->writeln('<comment>Starting import - '.date('Y-m-d H:i:s').'</comment>', OutputInterface::VERBOSITY_NORMAL);

        $companies = $this->legacyCompanyService->findAll(5, 10);

        $this->writeln(sprintf('<info>%s</info> Suppliers to import', count($companies)), OutputInterface::VERBOSITY_NORMAL);
        $loopCounter = 0;

        foreach ($companies as $company) {
            ++$loopCounter;
            //dump($mortician);
            $companyObject = $this->updateSupplier($company);
            $this->addAddress($company, $companyObject);
            $this->findHeadquater($company);
            $this->addUser($company, $companyObject);

            //if ($input->getOption('image')) {
            //    $this->storeImages($company, $mortObject);
            //}

            $this->writeln(
                sprintf('%s (%s) <info>%s</info>',
                    ($companyObject->getId() == null) ? 'Created' : 'Updated',
                    $companyObject->getOrigId(),
                    $companyObject->getName()
                ),
                OutputInterface::VERBOSITY_VERBOSE
            );

            $this->supplierService->flush();

            if ($loopCounter % 50 == 0) {
                // clear objects, speeds up import
                $this->legacyEntityManager->clear();
                $this->entityManager->clear();
            }
        }

        $this->saveRelations();
        $this->supplierService->flush();
    }

    /**
     * @param User     $company
     * @param Supplier $supplierObject
     */
    protected function addUser(User $company, Supplier $supplierObject)
    {
        if (($company->getEmail()) == '' || !filter_var($company->getEmail(), FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $user = $this->findUserOrNew($company->getEmail());
        $user->setFirstname('')
             ->setLastname($company->getContactPerson())
             ->setSupplier($supplierObject)
             ->setPlainPassword(Utils::generateRandomString(12))
             ->setEnabled(!$company->getBlock());

        $this->supplierService->persist($user);
        $this->userManager->updateUser($user);
    }

    /**
     * @param $email
     *
     * @return SupplierUser
     */
    protected function findUserOrNew($email)
    {
        $rep = $this->supplierService->getEm()->getRepository('Model:SupplierUser');

        try {
            $user = $rep->findOneBy(array('email' => $email));
            if ($user == null) {
                throw new EntityNotFoundException();
            }
        } catch (EntityNotFoundException $e) {
            $user = new SupplierUser();
            $user->setEmail($email);
        }

        return $user;
    }

    /**
     * @param User      $mortician
     * @param Mortician $mortObject
     */
    protected function storeImages(User $mortician, Mortician $mortObject)
    {
        $logo = $this->grabImage($mortician->getPhotoMore2());
        if ($logo != null) {
            $mortObject->setLogo($this->createImage($logo));
        }

        $avatar = $this->grabImage($mortician->getPhoto());
        if ($avatar != null) {
            $mortObject->setAvatar($this->createImage($avatar));
        }

        // we need to remove all existing media
        foreach ($mortObject->getMedias() as $oldMedia) {
            $this->morticianService->remove($oldMedia);
        }

        for ($i=3; $i<10; $i++) {
            $image = $this->grabImage($mortician->{"getPhotoMore".$i}());
            if ($image != null) {
                $media = $this->createImage($image);

                $morticianMedia = new MorticianMedia();
                $morticianMedia->setMortician($mortObject)
                               ->setMedia($media)
                               ->setDescription($mortician->{"getPhotoMore".$i."Description"}());
                $mortObject->addMedia($morticianMedia);
            }
        }
    }

    /**
     * @param $path
     *
     * @return \Cwd\MediaBundle\Model\Entity\Media
     * @throws \Cwd\MediaBundle\MediaException
     */
    protected function createImage($path)
    {
        $image = $this->mediaService->create($path, true);
        $this->morticianService->flush($image);
        unlink($path);

        return $image;
    }

    /**
     * @param $imageName
     *
     * @return null|string
     */
    protected function grabImage($imageName)
    {
        if (trim($imageName) == '') {
            return null;
        }

        try {
            $url = $this->imageUrl . $imageName;
            $location = tempnam('/tmp', 'aspetos');
            file_put_contents($location, file_get_contents($url));
        } catch (\Exception $e) {
            $this->writeln(sprintf('<error>%s</error>', $e->getMessage()), OutputInterface::VERBOSITY_VERBOSE);

            return null;
        }

        return $location;
    }

    /**
     * Update relations
     * @throws MorticianNotFoundException
     */
    protected function saveRelations()
    {
        foreach ($this->relations as $parent => $uid) {
            $child = $this->supplierService->findByUid($uid);
            $parent = $this->supplierService->findByUid($parent);
            $child->setParentSupplier($parent);
        }
    }

    /**
     * @param User $company
     */
    protected function findHeadquater(User $company)
    {
        $rep = $this->getLegacyEntityManager()->getRepository('Legacy:User2User');
        $results = $rep->findBy(array('type' => 'headquartersOf', 'uid' => $company->getUid()));
        foreach ($results as $result) {
            $this->relations[$company->getUid()] = $result->getUidTo();
        }
    }

    /**
     * @param User $company
     *
     * @return Supplier
     */
    protected function updateSupplier(User $company)
    {
        $supplierObject = $this->findOrNew($company->getUid());

        $supplierObject->setCountry(strtoupper($company->getDomain()))
                   ->setName($this->getValueOrNull($company->getName()))
                   ->setCommercialRegNumber($this->getValueOrNull($company->getCommercialRegNumber()))
                   ->setVat($this->getValueOrNull($company->getVatNumber()))
                   ->setDescription($this->getValueOrNull($company->getDescriptionMore1()))
                   ->setCommercialRegNumber($this->getValueOrNull($company->getCommercialRegNumber()))
                   ->setContactName($this->getValueOrNull($company->getContactPerson()))
                   ->setEmail($this->getValueOrNull($company->getEmail()))
                   ->setWebpage($this->getValueOrNull($company->getWww()))
                   ->setPhone($this->phoneNumberParser($company->getPhone(), $company->getDomain(), $company->getUid()))
                   ->setFax($this->phoneNumberParser($company->getFax(), $company->getDomain(), $company->getUid()))
                   ->setRegisteredAt($company->getRegisterDate())
                   ->setState(!$company->getBlock())
                   ->setParentSupplier(null)
                   ->setPartnerVienna($company->getPartnerWienerVerein());

        if ($supplierObject->getId() == null) {
            $this->supplierService->persist($supplierObject);
        }

        return $supplierObject;
    }

    /**
     * @param int $uid
     *
     * @return Supplier
     * @throws \Aspetos\Service\Exception\MorticianNotFoundException
     */
    protected function findOrNew($uid)
    {
        try {
            $supplierObject = $this->supplierService->findByUid($uid);
        } catch (SupplierNotFoundException $e) {
            $supplierObject = new Supplier();
            $supplierObject->setOrigId($uid);
        }

        return $supplierObject;
    }

    /**
     * @param User     $company
     * @param Supplier $companyObject
     *
     * @return SupplierAddress
     * @throws \Cwd\GenericBundle\Exception\PersistanceException
     */
    protected function addAddress(User $company, Supplier $companyObject)
    {
        $addressObject = $companyObject->getAddress();

        if ($addressObject === null) {
            $addressObject = new SupplierAddress();
            $addressObject->setSupplier($companyObject);
        }

        $addressObject->setLat($company->getGeoLat())
                      ->setLng($company->getGeoLng())
                      ->setCountry(strtoupper($company->getDomain()))
                      ->setStreet($this->getValueOrEmpty($company->getStreet()))
                      ->setZipcode($this->getValueOrEmpty($company->getZip()))
                      ->setCity($this->getValueOrNull($company->getPlace()))
                      ->setRegion($this->findRegionByProvince($company->getProvince()));

        if ($this->getValueOrEmpty($company->getDistrict()) != null) {
            try {
                $addressObject->setDistrict($this->findDistrictByLegacyNameViaId($company->getDistrict()));
            } catch (\Exception $e) {
                // not found.. ignore
            }
        }

        $this->supplierService->persist($addressObject);

        return $addressObject;
    }

    protected function findDistrictByLegacyNameViaId($id)
    {
        $district = $this->legacyEntityManager->find('Legacy:District', $id);
        if ($district === null) {
            throw new EntityNotFoundException;
        }

        $districtObject = $this->entityManager->getRepository('Model:District')->findOneBy(array('name' => $district->getName()));

        if ($districtObject === null) {
            throw new EntityNotFoundException;
        }

        return $districtObject;
    }
}
