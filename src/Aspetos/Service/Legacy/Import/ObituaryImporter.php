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
use Aspetos\Model\Entity\Customer;
use Aspetos\Model\Entity\Obituary;
use Aspetos\Model\Entity\Supplier;
use Aspetos\Model\Entity\SupplierMedia;
use Aspetos\Model\Entity\SupplierUser;
use Aspetos\Service\Exception\SupplierNotFoundException;
use Aspetos\Service\Legacy\Exception\ObituaryNotFoundException;
use Aspetos\Service\Legacy\ObituaryService as legacyObituaryService;
use Aspetos\Service\ObituaryService;
use Aspetos\Service\MorticianService;

use Cwd\GenericBundle\LegacyHelper\Utils;
use Cwd\MediaBundle\Service\MediaService;
use Doctrine\ORM\EntityNotFoundException;
use FOS\UserBundle\Model\UserManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use libphonenumber\PhoneNumberUtil;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\DateTime;
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

        if ($input->getOption('user')) {
            $this->importUser($input);
        }

        $this->writeln('<comment>Starting import - '.date('Y-m-d H:i:s').'</comment>', OutputInterface::VERBOSITY_NORMAL);

        $obituaries = $this->legacyObituaryService->findAll(100000);

        $this->writeln(sprintf('<info>%s</info> Suppliers to import', count($obituaries)), OutputInterface::VERBOSITY_NORMAL);
        $loopCounter = 0;

        foreach ($obituaries as $obituary) {
            ++$loopCounter;
            //dump($mortician);
            $object = $this->updateObituary($obituary);

            $this->storeRelation($obituary, $object);

            if ($input->getOption('image')) {
                //$this->storeImages($company, $companyObject);
            }

            $this->writeln(
                sprintf('%s (%s) <info>%s</info>',
                    ($object->getId() == null) ? 'Created' : 'Updated',
                    $object->getOrigId(),
                    $object->getFirstname().' '.$object->getLastname()
                ),
                OutputInterface::VERBOSITY_VERBOSE
            );

            $this->obituaryService->flush();

            if ($loopCounter % 50 == 0) {
                // clear objects, speeds up import
                $this->legacyEntityManager->clear();
                $this->entityManager->clear();
            }
        }

        //$this->saveRelations();
        //$this->obituary->flush();
    }

    public function storeRelation(User $obituary, Obituary $object)
    {
        $result  = $this->findMortician($obituary);
        if ($result != null) {
            try {
                $mortician = $this->morticianService->findByUid($result);
                $object->setMortician($mortician);
            } catch (\Exception $e) {
            }
        }

        $result = $this->findCustomer($obituary);
        if ($result != null) {
            try {
                $user = $this->legacyEntityManager->find('Legacy:User', $result);
                $customer = $this->findUser($user->getEmail());
                if ($customer != null && $customer->getId() != null) {
                    $object->setCustomer($customer);
                }
            } catch (\Exception $e) {
            }
        }
    }

    /**
     * @param InputInterface $input
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function importUser(InputInterface $input)
    {
        $this->writeln('<comment>Starting import users - '.date('Y-m-d H:i:s').'</comment>', OutputInterface::VERBOSITY_NORMAL);

        $users = $this->legacyObituaryService->findAllUsers();
        $loopCounter = 0;

        $this->writeln(sprintf('<info>%s</info> users to import', count($users)), OutputInterface::VERBOSITY_NORMAL);

        foreach ($users as $user) {
            ++$loopCounter;
            /** @var User $user */
            if ($user->getBlock() == 0) {
                $object = $this->findUserOrNew($user->getEmail());
                if ($object == null) {
                    continue;
                }

                $object->setFirstname(trim($user->getForename()." ".$user->getForename2()))
                       ->setPassword(Utils::generateRandomString(16))
                       ->addGroup($this->getEntityManager()->getReference('Model:Group', 4))
                       ->setLastname($user->getName())
                       ->setCreatedAt($user->getRegisterDate())
                       ->setActivatedAt($user->getActivationDate())
                       ->setDisclaimerAcceptedAt($user->getDisclaimerAccepted())
                       ->setEnabled(!$user->getBlock())
                       ->setNewsletter($user->getNewsletter());
                if ($user->getLastvisitDate() instanceof DateTime) {
                    $object->setLastLoginAt($user->getLastvisitDate());
                }

                $this->writeln(
                    sprintf('%s (%s) <info>%s</info>',
                        ($object->getId() == null) ? 'Created' : 'Updated',
                        $user->getUId(),
                        $object->getFirstname().' '.$object->getLastname()
                    ),
                    OutputInterface::VERBOSITY_VERBOSE
                );

                $this->getEntityManager()->flush($object);

            }


            if ($loopCounter % 200 == 0) {
                $this->legacyEntityManager->clear();
                $this->entityManager->clear();
            }
        }
    }


    /**
     * @param $email
     *
     * @return Customer
     */
    protected function findUserOrNew($email)
    {
        $rep = $this->obituaryService->getEm()->getRepository('Model:BaseUser');

        try {
            $user = $rep->findOneBy(array('email' => $email));
            if ($user == null) {
                throw new EntityNotFoundException();
            }
            if (!$user instanceof Customer) {
                return null;
            }
        } catch (EntityNotFoundException $e) {
            $user = new Customer();
            $user->setEmail($email);

            if (!$this->validator->validate($user, null, array('create'))) {
                return null;
            }
            $this->obituaryService->persist($user);
        }

        return $user;
    }

    /**
     * @param $email
     *
     * @return Customer
     */
    protected function findUser($email)
    {
        $rep = $this->obituaryService->getEm()->getRepository('Model:BaseUser');

        try {
            $user = $rep->findOneBy(array('email' => $email));
            if ($user == null) {
                throw new EntityNotFoundException();
            }
            if (!$user instanceof Customer) {
                return null;
            }
        } catch (EntityNotFoundException $e) {
            return null;
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

    protected function findMortician(User $obituary)
    {
        $rep = $this->getLegacyEntityManager()->getRepository('Legacy:User2User');
        $results = $rep->findBy(array('type' => 'morticianOf', 'uidTo' => $obituary->getUid()));
        foreach ($results as $result) {
            return $result->getUid();
        }

        return null;
    }

    protected function findCustomer(User $obituary)
    {
        $rep = $this->getLegacyEntityManager()->getRepository('Legacy:User2User');
        $results = $rep->findBy(array('type' => 'standardAdded', 'uidTo' => $obituary->getUid()));
        foreach ($results as $result) {
            return $result->getUid();
        }

        return null;
    }


    /**
     * @param User $company
     *
     * @return Obituary
     */
    protected function updateObituary(User $obituary)
    {

        $gender = Obituary::GENDER_UNDEF;

        if ($obituary->getGender() != null) {
            switch ($obituary->getGender()->getId()) {
                case 2:
                    $gender = Obituary::GENDER_FEMALE;
                    break;
                case 1:
                    $gender = Obituary::GENDER_MALE;
                    break;
            }
        }

        $age = $this->calcAge($obituary);
        if ($age != null && $age < 18) {
            $type = Obituary::TYPE_CHILD;
        } else if ($obituary->getProminent() == 1) {
            $type = Obituary::TYPE_PROMINENT;
        } else {
            $type = Obituary::TYPE_NORMAL;
        }

        $object = $this->findOrNew($obituary->getUid());
        $object->setFirstname(trim($obituary->getForename().' '.$obituary->getForename2()))
               ->setLastname($obituary->getName())
               ->setTitlePrefix($obituary->getSalutation())
               ->setGender($gender)
               ->setType($type)
               ->setCountry(strtoupper($obituary->getDomain()))
               ->setHide($obituary->getBlock())
               ->setDayOfBirth($obituary->getBirthdate())
               ->setDayOfDeath($obituary->getDeadDeathdate())
               ->setShowOnlyBirthYear($obituary->getBirthdateShowOnlyYear())
               ->setLegacyCemetery($obituary->getDeadCemetery());

        try {
            $district = $this->findDistrictByLegacyNameViaId($obituary->getDistrict());
            $object->setDistrict($district);
        } catch (\Exception $e) {
        }



        if ($object->getId() == null) {
            $this->obituaryService->persist($object);
        }

        return $object;
    }

    protected function calcAge(User $obituary)
    {
        if ($obituary->getBirthdate() != null && $obituary->getDeadDeathdate() != null) {
            $interval = $obituary->getDeadDeathdate()->diff($obituary->getBirthdate());

            return $interval->format("%y");
        }

        return null;
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
        } catch (\Exception $e) {
            $object = new Obituary();
            $object->setOrigId($uid);
        }

        return $object;
    }


    protected function findDistrictByLegacyNameViaId($id)
    {
        if ($id == null) {
            throw new EntityNotFoundException;
        }

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
