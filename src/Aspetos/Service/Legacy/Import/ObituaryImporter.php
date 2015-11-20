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
use Aspetos\Bundle\LegacyBundle\Model\Entity\UserDead;
use Aspetos\Model\Entity\Customer;
use Aspetos\Model\Entity\Obituary;
use Aspetos\Model\Entity\ObituaryEvent;
use Aspetos\Model\Entity\ObituaryMedia;
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

    protected $imageTypes = array(
        'deathnoticeStandard' => ObituaryMedia::TYPE_OBITUARY,
        'thankyounote' => ObituaryMedia::TYPE_THANKYOU,
        'mourningpicture' => ObituaryMedia::TYPE_MOURNING,
        'deathnoticeMore1' => ObituaryMedia::TYPE_DEATHNOTICE1,
        'deathnoticeMore2' => ObituaryMedia::TYPE_DEATHNOTICE2,
        'deathnoticeMore3' => ObituaryMedia::TYPE_DEATHNOTICE3,
        'deathnoticeMoreYear1' => ObituaryMedia::TYPE_ANNIVERSARY1,
        'deathnoticeMoreYear2' => ObituaryMedia::TYPE_ANNIVERSARY2,
        'deathnoticeMoreYear3' => ObituaryMedia::TYPE_ANNIVERSARY3,
        'portrait' => ObituaryMedia::TYPE_PORTRAIT
    );

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

        $obituaries = $this->legacyObituaryService->findAll(100000, $input->getOption('offset'));
        //$obituaries = $this->legacyObituaryService->findAll(100000);
        $count = count($obituaries);

        $this->writeln(sprintf('<info>%s</info> Obituaries to import', $count), OutputInterface::VERBOSITY_NORMAL);
        $loopCounter = 0;

        foreach ($obituaries as $obituary) {
            ++$loopCounter;
            //dump($obituary->getUid());
            $object = $this->updateObituary($obituary);

            if ($input->getOption('image')) {
                $this->storeImages($obituary, $object);
            }

            $this->importEvents($obituary, $object);

            $this->storeRelation($obituary, $object);

            $this->writeln(
                sprintf('%s: %s (%s) <info>%s</info> %s',
                    $loopCounter.'/'.$count,
                    ($object->getId() == null) ? 'Created' : 'Updated',
                    $object->getOrigId(),
                    $object->getFirstname().' '.$object->getLastname(),
                    ($object->getMedias() !== null) ? $object->getMedias()->count() : 0
                ),
                OutputInterface::VERBOSITY_NORMAL
            );

            $this->obituaryService->flush();

            if ($loopCounter % 50 == 0) {
                // clear objects, speeds up import
                //$this->legacyEntityManager->clear();
                $this->entityManager->clear();
            }
        }

        //$this->saveRelations();
    }

    /**
     * @param User     $obituary
     * @param Obituary $object
     */
    public function importEvents(User $obituary, Obituary $object)
    {
        $res = $this->getLegacyEntityManager()->getRepository('Legacy:UserDead');
        /** @var UserDead $row */
        $row = $res->findOneBy(array('uid' => $obituary->getUid(), 'deathnoticeStandard' => 'deathnotice'));
        if ($row === null) {
            return;
        }

        $this->addEvent($object, $row->getDateTime1(), $row->getDateTitleStandard1(), $row->getDateDescription1(), $row->getDateTitle1());
        $this->addEvent($object, $row->getDateTime2(), $row->getDateTitleStandard2(), $row->getDateDescription2(), $row->getDateTitle2());
        $this->addEvent($object, $row->getDateTime3(), $row->getDateTitleStandard3(), $row->getDateDescription3(), $row->getDateTitle3());

    }

    protected function addEvent(Obituary $object, $date, $type, $description, $title)
    {
        if ($date == null) {
            return;
        }

        $resp = $this->getEntityManager()->getRepository('Model:ObituaryEventType');
        $eventType = $resp->findOneBy(array('name' => $type));
        if ($eventType == null) {
            $eventType = $resp->findOneBy(array('name' => $title));
            if ($eventType == null) {
                // Yes i know its ugly... but i dont care for legacy anymore
                return;
            }
        }

        $event = $this->findEventOrNew($object, $date, $eventType);
        $event->setObituaryEventType($eventType)
              ->setDateStart($date)
              ->setDescription($description);
        $this->getEntityManager()->persist($event);
    }

    protected function findEventOrNew(Obituary $object, $date, $eventType)
    {
        $resp = $this->getEntityManager()->getRepository('Model:ObituaryEvent');
        try {
            $event = $resp->findOneBy(array(
                'dateStart' => $date,
                'type' => $eventType,
                'obituary' => $object
            ));

            if ($event === null) {
                throw new \Exception('not found');
            }
        } catch (\Exception $e) {
            $event = new ObituaryEvent();
            $event->setObituary($object);
            $this->getEntityManager()->persist($event);
        }

        return $event;
    }

    /**
     * @param User     $obituary
     * @param Obituary $object
     */
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
                       ->setNewsletter($user->getNewsletter())
                       ->setForumId($user->getUidForum());
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
     * @param User     $obituary
     * @param Obituary $object
     */
    protected function storeImages(User $obituary, Obituary $object)
    {
        $galleryIds = $this->findGallery($obituary);
        if (count($galleryIds) == 0) {
            return;
        }

        foreach ($galleryIds as $gallery) {
            $galleryId = $gallery->getGid();
            $medias = $this->getLegacyEntityManager()->getRepository('Legacy:Media')->findMedia($galleryId);

            foreach ($medias as $media) {
                if (isset($media['mid'])) {
                    $image = $this->grabImage($this->generateFileUrl($media['mid']));

                    //dump($this->generateFileUrl($media['mid']));
                    //dump($media['filename']);

                    if ($image != null) {
                        $mediaObj = $this->createImage($image);

                        //dump($image);


                        if ($mediaObj != null) {
                            //dump('saving image');
                            $imageObject = $this->findMediaOrNew($media['mid']);
                            $imageObject->setObituary($object)
                                ->setType($this->imageTypes[$media['deathnoticeType']])
                                ->setDescription($media['description'])
                                ->setMedia($mediaObj);

                            $this->obituaryService->flush($imageObject);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param int $mid
     *
     * @return ObituaryMedia
     */
    protected function findMediaOrNew($mid)
    {
        try {
            $media = $this->getEntityManager()->getRepository('Model:ObituaryMedia')->findOneBy(array('origId' => $mid));
            if ($media == null) {
                throw new \Exception('not found');
            }
        } catch (\Exception $e) {
            $media = new ObituaryMedia();
            $media->setOrigId($mid);
            $this->obituaryService->persist($media);
        }

        return $media;
    }

    protected function generateFileUrl($mid)
    {
        $rsa = openssl_pkey_get_private('-----BEGIN RSA PRIVATE KEY-----
MIICWwIBAAKBgQDJVx+QsO3A7iboQprTAgIRp2kGsiABXJk1TIo0gdQgA2Ckj3QP
4NWVui+FfMHhPBElVQZim5PMVYLy1K0P5TWRb+pZae9MWd496s/DLAhkttTD/wIL
ox70oMDltDDzjIGUi8dkXtoEwDP/ccrxfxBIyF/akpMOxVBwqz//29ggAwIDAQAB
AoGACyIuNKoT+58vt5g3R4PM6X0AacZfzrN4JWJeIhkryblmFrN4D4i3RthM2GzV
q8bOi+nvuHQx8BKqcxMfnsll9NOtY54Nn+k21Jie3HKDwg3Q1upXPMvdtwnG6x8X
LfIUa9A5zd/CZZ4MS+HrtITiIhyaLy+vXEZJx6j+l3ZROIECQQD4gUp9iAFcb5bl
pweg6HQ/FwFFlDDnYeDobCtb4YM3huMpKh+dHg8TuJiM9S5Hy+IINJLEhQHE0Ono
oeBv2tlJAkEAz2msWWJZw+D2LQnbqH8fQvsGUHJvtMeb8z947nhWP1ZkM0/7Ljfo
FFRFrXt0Dqx0ZyQ7PXyI7fyQK9qO+Bda6wJAF68K0Gfz6UFhWkXO/lDpM1IX9u+m
sYasjrowb7NQdCxQ3g17sde5jCGduGFtpb4SrsGD82LRxlY61McIwuBSsQJAaZjt
bFyMQxPHgcqhELoX2mwfHNoGUU3G8iMAmLifgIOlZBJ2WWORPrhg+R//SHE4mkJx
/q3msPODGzCc1WZenQJAT7kusmoXcv+sd8mIyC0S5thkfxAMSH0VnFdN2o/YJpQf
XLI29ZUiOKGEA0gl96qvLfQRuW+Qst29DRPxAuVTmQ==
-----END RSA PRIVATE KEY-----');

        $mtypeForKey = 'photo';
        // filenames is changed all the time.... :(
        $cacheLifetime=34200;
        $time = intval(time()/$cacheLifetime) * $cacheLifetime;
        $lifetime = $time+86400;
        $style = 'full';
        openssl_private_encrypt($lifetime.'#'.$mtypeForKey.'#'.$mid.'#'.$style, $crypted, $rsa);

        return 'http://www.aspetos.at/show/'.rtrim(strtr(base64_encode($crypted), '+/', '-_'), '=');
    }

    /**
     * @param User $obituary
     *
     * @return int
     */
    protected function findGallery(User $obituary)
    {
        return $this->getLegacyEntityManager()->getRepository('Legacy:Gallery')->findBy(array('uid' => $obituary->getUid(), 'hide' => 0), array('sort' => 'ASC'));
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
        $this->obituaryService->flush($image);
        unlink($path);

        return $image;
    }

    /**
     * @param $imageName
     *
     * @return null|string
     */
    protected function grabImage($url)
    {
        if (trim($url) == '') {
            return null;
        }

        try {
            $location = tempnam('/tmp', 'aspetos');
            file_put_contents($location, file_get_contents($url));
        } catch (\Exception $e) {
            $this->writeln(sprintf('<error>%s</error>', $e->getMessage()), OutputInterface::VERBOSITY_NORMAL);

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
