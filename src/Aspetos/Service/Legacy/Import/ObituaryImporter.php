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
use Aspetos\Model\Entity\Obituary;
use Aspetos\Model\Entity\Supplier;
use Aspetos\Model\Entity\SupplierMedia;
use Aspetos\Model\Entity\SupplierUser;
use Aspetos\Service\Exception\SupplierNotFoundException;
use Aspetos\Service\Legacy\ObituaryService as legacyObituaryService;
use Aspetos\Service\ObituaryService;
use Aspetos\Service\MorticianService;

use Cwd\MediaBundle\Service\MediaService;
use Doctrine\ORM\EntityNotFoundException;
use FOS\UserBundle\Model\UserManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use libphonenumber\PhoneNumberUtil;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ObituaryImporter
 *
 * @package Aspetos\Service\Legacy\Import
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.legacy.import.obituary", parent="aspetos.service.legacy.import.base")
 */
class ObituaryImporter extends BaseImporter
{
    /**
     * @var legacyObituaryService
     */
    protected $legacyObituaryService;

    /**
     * @var ObituaryService
     */
    protected $obituaryService;

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
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var MorticianService
     */
    protected $morticianService;

    /**
     * @param legacyObituaryService $legacyObituaryService
     * @param ObituaryService       $obituaryService
     * @param PhoneNumberUtil       $phoneNumberUtil
     * @param MediaService          $mediaService
     * @param UserManagerInterface  $userManager
     * @param Validator             $validator
     * @param MorticianService      $morticianService
     *
     * @DI\InjectParams({
     *     "legacyObituaryService" = @DI\Inject("aspetos.service.legacy.obituary"),
     *     "obituaryService" = @DI\Inject("aspetos.service.obituary"),
     *     "phoneNumberUtil"  = @DI\Inject("libphonenumber.phone_number_util"),
     *     "mediaService"     = @DI\Inject("cwd.media.service"),
     *     "userManager"      = @DI\Inject("fos_user.user_manager"),
     *     "validator"        = @DI\Inject("validator"),
     *     "morticianService" = @DI\Inject("aspetos.service.mortician")
     * })
     */
    public function __construct(
        legacyObituaryService $legacyObituaryService,
        ObituaryService $obituaryService,
        PhoneNumberUtil $phoneNumberUtil,
        MediaService $mediaService,
        UserManagerInterface $userManager,
        $validator,
        MorticianService $morticianService
        )
    {
        $this->legacyObituaryService = $legacyObituaryService;
        $this->obituaryService = $obituaryService;
        $this->mediaService = $mediaService;
        $this->userManager = $userManager;
        $this->validator = $validator;
        $this->morticianService = $morticianService;

        parent::__construct($obituaryService->getEm(), $legacyObituaryService->getEm(), $phoneNumberUtil);
    }

    /**
     * @param InputInterface $input
     */
    public function run(InputInterface $input)
    {
        $this->writeln('<comment>Starting import - '.date('Y-m-d H:i:s').'</comment>', OutputInterface::VERBOSITY_NORMAL);

        $obituaries = $this->legacyObituaryService->findAll(1, 500);

        dump($obituaries);return;

        $this->writeln(sprintf('<info>%s</info> Suppliers to import', count($obituary)), OutputInterface::VERBOSITY_NORMAL);
        $loopCounter = 0;

        foreach ($obituaries as $obituary) {
            ++$loopCounter;
            //dump($mortician);
            $companyObject = $this->updateObituary($obituary);


            //$this->addMortician($company, $companyObject);

            if ($input->getOption('image')) {
                //$this->storeImages($company, $companyObject);
            }

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
     * @param $email
     *
     * @return SupplierUser
     */
    protected function findUserOrNew($email)
    {
        $rep = $this->supplierService->getEm()->getRepository('Model:BaseUser');

        try {
            $user = $rep->findOneBy(array('email' => $email));
            if ($user == null) {
                throw new EntityNotFoundException();
            }
            if (!$user instanceof SupplierUser) {
                return null;
            }
        } catch (EntityNotFoundException $e) {
            $user = new SupplierUser();
            $user->setEmail($email);

            if (!$this->validator->validate($user, null, array('create'))) {
                return null;
            }
            $this->supplierService->persist($user);
        }

        return $user;
    }

    /**
     * @param User     $company
     * @param Supplier $companyObject
     */
    protected function storeImages(User $company, Supplier $companyObject)
    {
        $logo = $this->grabImage($company->getPhotoMore2());
        if ($logo != null) {
           $companyObject->setLogo($this->createImage($logo));
        }

        $avatar = $this->grabImage($company->getPhoto());
        if ($avatar != null) {
           $companyObject->setAvatar($this->createImage($avatar));
        }

        // we need to remove all existing media
        foreach ($companyObject->getMedias() as $oldMedia) {
            $this->morticianService->remove($oldMedia);
        }

        for ($i=3; $i<10; $i++) {
            $image = $this->grabImage($company->{"getPhotoMore".$i}());
            if ($image != null) {
                $media = $this->createImage($image);

                $companyMedia = new SupplierMedia();
                $companyMedia->setSupplier($companyObject)
                               ->setMedia($media)
                               ->setDescription($company->{"getPhotoMore".$i."Description"}());
               $companyObject->addMedia($companyMedia);
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
        $this->supplierService->flush($image);
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

    protected function findMorticians(User $company)
    {
        $return = array();
        $rep = $this->getLegacyEntityManager()->getRepository('Legacy:User2User');
        $results = $rep->findBy(array('type' => 'companyOf', 'uid' => $company->getUid()));
        foreach ($results as $result) {
            $return[] = $result->getUidTo();
        }

        return $return;
    }

    protected function addMortician(User $company, Supplier $companyObject)
    {
        $morticians = $this->findMorticians($company);

        foreach ($morticians as $mortician) {
            $mortObject = $this->morticianService->findByUid($mortician);
            $companyObject->addMortician($mortObject);
        }
    }

    /**
     * @param User $company
     *
     * @return Obituary
     */
    protected function updateObituary(User $obituary)
    {
        switch ($obituary->getGender()) {
            case 2:
                $gender = Obituary::GENDER_FEMALE;
                break;
            case 1:
                $gender = Obituary::GENDER_MALE;
                break;
            default:
                $gender = Obituary::GENDER_UNDEF;
        }

        $object = $this->findOrNew($obituary->getUid());
        $object->setFirstname(trim($obituary->getForename().' '.$obituary->getForename2()))
               ->setLastname($obituary->getName())
               ->setTitlePrefix($obituary->getSalutation())
               ->setGender($gender)
               ->setCountry(strtoupper($obituary->getDomain()))
               ->setHide($obituary->getBlock())
               ->setDayOfBirth($obituary->getBirthdate())
               ->setDayOfDeath($obituary->getDeadDeathdate())
               ->setShowOnlyBirthYear($obituary->getBirthdateShowOnlyYear());


        if ($object->getId() == null) {
            $this->obituaryService->persist($object);
        }

        return $object;
    }

    /**
     * @param int $uid
     *
     * @return Obituary
     * @throws \Aspetos\Service\Exception\ObituaryNotFoundException
     */
    protected function findOrNew($uid)
    {
        try {
            $object = $this->obituaryService->findByUid($uid);
        } catch (SupplierNotFoundException $e) {
            $object = new Obituary();
            $object->setOrigId($uid);
        }

        return $object;
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
