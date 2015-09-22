<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Bundle\LegacyBundle\Model\Repository\UserRepository")
 * @ORM\Table(
 *     name="es_user",
 *     indexes={
 *         @ORM\Index(name="usertype", columns={"usertype"}),
 *         @ORM\Index(name="provinceId", columns={"provinceId"}),
 *         @ORM\Index(name="deadDistrict", columns={"deadDistrict"}),
 *         @ORM\Index(name="gender", columns={"gender"}),
 *         @ORM\Index(name="countryId2", columns={"countryId2"}),
 *         @ORM\Index(name="provinceId2", columns={"provinceId2"}),
 *         @ORM\Index(name="districtId2", columns={"districtId2"}),
 *         @ORM\Index(name="countryId3", columns={"countryId3"}),
 *         @ORM\Index(name="provinceId3", columns={"provinceId3"}),
 *         @ORM\Index(name="districtId3", columns={"districtId3"}),
 *         @ORM\Index(name="countryId", columns={"countryId"}),
 *         @ORM\Index(name="districtId", columns={"districtId"}),
 *         @ORM\Index(name="es_user_userCategory", columns={"userCategory"}),
 *         @ORM\Index(name="domain", columns={"domain"})
 *     },
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="username", columns={"username"}),
 *         @ORM\UniqueConstraint(name="prettyUrl", columns={"prettyUrl"}),
 *         @ORM\UniqueConstraint(name="uidOldSystem", columns={"uidOldSystem"})
 *     }
 * )
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $uid;

    /**
     * @ORM\Column(type="string", nullable=false, options={"default":"'at'"})
     */
    private $domain;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $uidOldSystem;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $uidForum;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $birthName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $salutation;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $forename;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $forename2;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $generation;

    /**
     * @ORM\Column(type="string", length=300, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=500, nullable=true, options={"default":"NULL"})
     */
    private $shortName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $contactPerson;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $academicTitle;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $maritalStatus;

    /**
     * @ORM\Column(type="string", length=25, nullable=false, options={"default":""})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $profession;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $username_random;

    /**
     * @ORM\Column(type="string", length=100, nullable=false, options={"default":""})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $emailFamily;

    /**
     * @ORM\Column(type="string", length=100, nullable=false, options={"default":""})
     */
    private $password;

    /**
     * @ORM\Column(type="string", nullable=false, options={"default":"'registered'"})
     */
    private $usertype;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"'standard'"})
     */
    private $userCategory;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false, options={"default":"'0'"})
     */
    private $block;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":"NULL"})
     */
    private $registerDate;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":"NULL"})
     */
    private $activationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":"NULL"})
     */
    private $lastvisitDate;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":"NULL"})
     */
    private $disclaimerAccepted;

    /**
     * @ORM\Column(type="string", length=100, nullable=false, options={"default":""})
     */
    private $activation;

    /**
     * @ORM\Column(type="date", nullable=true, options={"default":"NULL"})
     */
    private $birthdate;

    /**
     * @ORM\Column(type="integer", length=1, nullable=true, options={"default":"'0'"})
     */
    private $birthdateShowOnlyYear;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $birthPlace;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false)
     */
    private $trader;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false, options={"default":"'0'"})
     */
    private $fakeCheck;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore5;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore6;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore7;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore8;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore9;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore1Description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore2Description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore3Description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore4Description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore5Description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore6Description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore7Description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore8Description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $photoMore9Description;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $zip;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $place;

    /**
     * @ORM\Column(type="decimal", length=12, nullable=true, scale=8, options={"default":"NULL"})
     */
    private $geoLat;

    /**
     * @ORM\Column(type="decimal", length=12, nullable=true, scale=8, options={"default":"NULL"})
     */
    private $geoLng;

    /**
     * @ORM\Column(type="integer", length=1, nullable=true, options={"default":"'0'"})
     */
    private $geoManual;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $phone2;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $www;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $vatNumber;

    /**
     * @ORM\Column(type="string", length=45, nullable=true, options={"default":"NULL"})
     */
    private $commercialRegNumber;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $descriptionMore1;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $descriptionMore2;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $payment;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $accountnumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $bank_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $bank_account;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $bank_number;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $swift;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $iban;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $cc_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $cc_number;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $cc_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $cc_expiration;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $religion;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $marrigePlace;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":"NULL"})
     */
    private $marrigeDate;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false, options={"default":"'0'"})
     */
    private $newsletter;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false, options={"default":"'0'"})
     */
    private $newsletterFriends;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false, options={"default":"'0'"})
     */
    private $backDoor;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false, options={"default":"'0'"})
     */
    private $ftp_enabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $ftp_password;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $balance_view;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $deadPlace;

    /**
     * @ORM\Column(type="date", nullable=true, options={"default":"NULL"})
     */
    private $deadDeathdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $deadCemetery;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $deadHide;

    /**
     * @ORM\Column(type="integer", length=2, nullable=false)
     */
    private $deadAutopsy;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $deadCoronerPlace;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":"NULL"})
     */
    private $deadCoronerDate;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false, options={"default":"'0'"})
     */
    private $partnerWienerVerein;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false, options={"default":"'0'"})
     */
    private $prominent;

    /**
     * @ORM\Column(type="string", length=100, nullable=true, options={"default":"NULL"})
     */
    private $externalKey;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $messages_view;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $XXXschreibzeichen;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $XXXnationalitaet;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $XXXdownload;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $XXXlieferort;

    /**
     * @ORM\Column(type="string", length=16, nullable=true, options={"default":"NULL"})
     */
    private $XXXrechte;

    /**
     * @ORM\Column(type="date", nullable=true, options={"default":"NULL"})
     */
    private $premiumUntil;

    /**
     * @ORM\Column(type="string", nullable=false, options={"default":"'noAccess'"})
     */
    private $access;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $prettyUrl;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":"NULL"})
     */
    private $imported;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":"CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"})
     */
    private $timestamp;

    /**
     * @ORM\ManyToOne(targetEntity="Province")
     * @ORM\JoinColumn(name="provinceId", referencedColumnName="provinceId")
     */
    private $province;

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     *
     * @return $this
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     *
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUidOldSystem()
    {
        return $this->uidOldSystem;
    }

    /**
     * @param mixed $uidOldSystem
     *
     * @return $this
     */
    public function setUidOldSystem($uidOldSystem)
    {
        $this->uidOldSystem = $uidOldSystem;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUidForum()
    {
        return $this->uidForum;
    }

    /**
     * @param mixed $uidForum
     *
     * @return $this
     */
    public function setUidForum($uidForum)
    {
        $this->uidForum = $uidForum;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthName()
    {
        return $this->birthName;
    }

    /**
     * @param mixed $birthName
     *
     * @return $this
     */
    public function setBirthName($birthName)
    {
        $this->birthName = $birthName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * @param mixed $salutation
     *
     * @return $this
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getForename()
    {
        return $this->forename;
    }

    /**
     * @param mixed $forename
     *
     * @return $this
     */
    public function setForename($forename)
    {
        $this->forename = $forename;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getForename2()
    {
        return $this->forename2;
    }

    /**
     * @param mixed $forename2
     *
     * @return $this
     */
    public function setForename2($forename2)
    {
        $this->forename2 = $forename2;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGeneration()
    {
        return $this->generation;
    }

    /**
     * @param mixed $generation
     *
     * @return $this
     */
    public function setGeneration($generation)
    {
        $this->generation = $generation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param mixed $shortName
     *
     * @return $this
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContactPerson()
    {
        return $this->contactPerson;
    }

    /**
     * @param mixed $contactPerson
     *
     * @return $this
     */
    public function setContactPerson($contactPerson)
    {
        $this->contactPerson = $contactPerson;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAcademicTitle()
    {
        return $this->academicTitle;
    }

    /**
     * @param mixed $academicTitle
     *
     * @return $this
     */
    public function setAcademicTitle($academicTitle)
    {
        $this->academicTitle = $academicTitle;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    /**
     * @param mixed $maritalStatus
     *
     * @return $this
     */
    public function setMaritalStatus($maritalStatus)
    {
        $this->maritalStatus = $maritalStatus;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     *
     * @return $this
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * @param mixed $profession
     *
     * @return $this
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsernameRandom()
    {
        return $this->username_random;
    }

    /**
     * @param mixed $username_random
     *
     * @return $this
     */
    public function setUsernameRandom($username_random)
    {
        $this->username_random = $username_random;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailFamily()
    {
        return $this->emailFamily;
    }

    /**
     * @param mixed $emailFamily
     *
     * @return $this
     */
    public function setEmailFamily($emailFamily)
    {
        $this->emailFamily = $emailFamily;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsertype()
    {
        return $this->usertype;
    }

    /**
     * @param mixed $usertype
     *
     * @return $this
     */
    public function setUsertype($usertype)
    {
        $this->usertype = $usertype;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserCategory()
    {
        return $this->userCategory;
    }

    /**
     * @param mixed $userCategory
     *
     * @return $this
     */
    public function setUserCategory($userCategory)
    {
        $this->userCategory = $userCategory;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param mixed $block
     *
     * @return $this
     */
    public function setBlock($block)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegisterDate()
    {
        return $this->registerDate;
    }

    /**
     * @param mixed $registerDate
     *
     * @return $this
     */
    public function setRegisterDate($registerDate)
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivationDate()
    {
        return $this->activationDate;
    }

    /**
     * @param mixed $activationDate
     *
     * @return $this
     */
    public function setActivationDate($activationDate)
    {
        $this->activationDate = $activationDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastvisitDate()
    {
        return $this->lastvisitDate;
    }

    /**
     * @param mixed $lastvisitDate
     *
     * @return $this
     */
    public function setLastvisitDate($lastvisitDate)
    {
        $this->lastvisitDate = $lastvisitDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisclaimerAccepted()
    {
        return $this->disclaimerAccepted;
    }

    /**
     * @param mixed $disclaimerAccepted
     *
     * @return $this
     */
    public function setDisclaimerAccepted($disclaimerAccepted)
    {
        $this->disclaimerAccepted = $disclaimerAccepted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivation()
    {
        return $this->activation;
    }

    /**
     * @param mixed $activation
     *
     * @return $this
     */
    public function setActivation($activation)
    {
        $this->activation = $activation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param mixed $birthdate
     *
     * @return $this
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthdateShowOnlyYear()
    {
        return $this->birthdateShowOnlyYear;
    }

    /**
     * @param mixed $birthdateShowOnlyYear
     *
     * @return $this
     */
    public function setBirthdateShowOnlyYear($birthdateShowOnlyYear)
    {
        $this->birthdateShowOnlyYear = $birthdateShowOnlyYear;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthPlace()
    {
        return $this->birthPlace;
    }

    /**
     * @param mixed $birthPlace
     *
     * @return $this
     */
    public function setBirthPlace($birthPlace)
    {
        $this->birthPlace = $birthPlace;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrader()
    {
        return $this->trader;
    }

    /**
     * @param mixed $trader
     *
     * @return $this
     */
    public function setTrader($trader)
    {
        $this->trader = $trader;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFakeCheck()
    {
        return $this->fakeCheck;
    }

    /**
     * @param mixed $fakeCheck
     *
     * @return $this
     */
    public function setFakeCheck($fakeCheck)
    {
        $this->fakeCheck = $fakeCheck;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     *
     * @return $this
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore1()
    {
        return $this->photoMore1;
    }

    /**
     * @param mixed $photoMore1
     *
     * @return $this
     */
    public function setPhotoMore1($photoMore1)
    {
        $this->photoMore1 = $photoMore1;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore2()
    {
        return $this->photoMore2;
    }

    /**
     * @param mixed $photoMore2
     *
     * @return $this
     */
    public function setPhotoMore2($photoMore2)
    {
        $this->photoMore2 = $photoMore2;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore3()
    {
        return $this->photoMore3;
    }

    /**
     * @param mixed $photoMore3
     *
     * @return $this
     */
    public function setPhotoMore3($photoMore3)
    {
        $this->photoMore3 = $photoMore3;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore4()
    {
        return $this->photoMore4;
    }

    /**
     * @param mixed $photoMore4
     *
     * @return $this
     */
    public function setPhotoMore4($photoMore4)
    {
        $this->photoMore4 = $photoMore4;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore5()
    {
        return $this->photoMore5;
    }

    /**
     * @param mixed $photoMore5
     *
     * @return $this
     */
    public function setPhotoMore5($photoMore5)
    {
        $this->photoMore5 = $photoMore5;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore6()
    {
        return $this->photoMore6;
    }

    /**
     * @param mixed $photoMore6
     *
     * @return $this
     */
    public function setPhotoMore6($photoMore6)
    {
        $this->photoMore6 = $photoMore6;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore7()
    {
        return $this->photoMore7;
    }

    /**
     * @param mixed $photoMore7
     *
     * @return $this
     */
    public function setPhotoMore7($photoMore7)
    {
        $this->photoMore7 = $photoMore7;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore8()
    {
        return $this->photoMore8;
    }

    /**
     * @param mixed $photoMore8
     *
     * @return $this
     */
    public function setPhotoMore8($photoMore8)
    {
        $this->photoMore8 = $photoMore8;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore9()
    {
        return $this->photoMore9;
    }

    /**
     * @param mixed $photoMore9
     *
     * @return $this
     */
    public function setPhotoMore9($photoMore9)
    {
        $this->photoMore9 = $photoMore9;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore1Description()
    {
        return $this->photoMore1Description;
    }

    /**
     * @param mixed $photoMore1Description
     *
     * @return $this
     */
    public function setPhotoMore1Description($photoMore1Description)
    {
        $this->photoMore1Description = $photoMore1Description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore2Description()
    {
        return $this->photoMore2Description;
    }

    /**
     * @param mixed $photoMore2Description
     *
     * @return $this
     */
    public function setPhotoMore2Description($photoMore2Description)
    {
        $this->photoMore2Description = $photoMore2Description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore3Description()
    {
        return $this->photoMore3Description;
    }

    /**
     * @param mixed $photoMore3Description
     *
     * @return $this
     */
    public function setPhotoMore3Description($photoMore3Description)
    {
        $this->photoMore3Description = $photoMore3Description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore4Description()
    {
        return $this->photoMore4Description;
    }

    /**
     * @param mixed $photoMore4Description
     *
     * @return $this
     */
    public function setPhotoMore4Description($photoMore4Description)
    {
        $this->photoMore4Description = $photoMore4Description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore5Description()
    {
        return $this->photoMore5Description;
    }

    /**
     * @param mixed $photoMore5Description
     *
     * @return $this
     */
    public function setPhotoMore5Description($photoMore5Description)
    {
        $this->photoMore5Description = $photoMore5Description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore6Description()
    {
        return $this->photoMore6Description;
    }

    /**
     * @param mixed $photoMore6Description
     *
     * @return $this
     */
    public function setPhotoMore6Description($photoMore6Description)
    {
        $this->photoMore6Description = $photoMore6Description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore7Description()
    {
        return $this->photoMore7Description;
    }

    /**
     * @param mixed $photoMore7Description
     *
     * @return $this
     */
    public function setPhotoMore7Description($photoMore7Description)
    {
        $this->photoMore7Description = $photoMore7Description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore8Description()
    {
        return $this->photoMore8Description;
    }

    /**
     * @param mixed $photoMore8Description
     *
     * @return $this
     */
    public function setPhotoMore8Description($photoMore8Description)
    {
        $this->photoMore8Description = $photoMore8Description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoMore9Description()
    {
        return $this->photoMore9Description;
    }

    /**
     * @param mixed $photoMore9Description
     *
     * @return $this
     */
    public function setPhotoMore9Description($photoMore9Description)
    {
        $this->photoMore9Description = $photoMore9Description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     *
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param mixed $zip
     *
     * @return $this
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     *
     * @return $this
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGeoLat()
    {
        return $this->geoLat;
    }

    /**
     * @param mixed $geoLat
     *
     * @return $this
     */
    public function setGeoLat($geoLat)
    {
        $this->geoLat = $geoLat;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGeoLng()
    {
        return $this->geoLng;
    }

    /**
     * @param mixed $geoLng
     *
     * @return $this
     */
    public function setGeoLng($geoLng)
    {
        $this->geoLng = $geoLng;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGeoManual()
    {
        return $this->geoManual;
    }

    /**
     * @param mixed $geoManual
     *
     * @return $this
     */
    public function setGeoManual($geoManual)
    {
        $this->geoManual = $geoManual;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * @param mixed $phone2
     *
     * @return $this
     */
    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param mixed $fax
     *
     * @return $this
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWww()
    {
        return $this->www;
    }

    /**
     * @param mixed $www
     *
     * @return $this
     */
    public function setWww($www)
    {
        $this->www = $www;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVatNumber()
    {
        return $this->vatNumber;
    }

    /**
     * @param mixed $vatNumber
     *
     * @return $this
     */
    public function setVatNumber($vatNumber)
    {
        $this->vatNumber = $vatNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommercialRegNumber()
    {
        return $this->commercialRegNumber;
    }

    /**
     * @param mixed $commercialRegNumber
     *
     * @return $this
     */
    public function setCommercialRegNumber($commercialRegNumber)
    {
        $this->commercialRegNumber = $commercialRegNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescriptionMore1()
    {
        return $this->descriptionMore1;
    }

    /**
     * @param mixed $descriptionMore1
     *
     * @return $this
     */
    public function setDescriptionMore1($descriptionMore1)
    {
        $this->descriptionMore1 = $descriptionMore1;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescriptionMore2()
    {
        return $this->descriptionMore2;
    }

    /**
     * @param mixed $descriptionMore2
     *
     * @return $this
     */
    public function setDescriptionMore2($descriptionMore2)
    {
        $this->descriptionMore2 = $descriptionMore2;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param mixed $payment
     *
     * @return $this
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountnumber()
    {
        return $this->accountnumber;
    }

    /**
     * @param mixed $accountnumber
     *
     * @return $this
     */
    public function setAccountnumber($accountnumber)
    {
        $this->accountnumber = $accountnumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankName()
    {
        return $this->bank_name;
    }

    /**
     * @param mixed $bank_name
     *
     * @return $this
     */
    public function setBankName($bank_name)
    {
        $this->bank_name = $bank_name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankAccount()
    {
        return $this->bank_account;
    }

    /**
     * @param mixed $bank_account
     *
     * @return $this
     */
    public function setBankAccount($bank_account)
    {
        $this->bank_account = $bank_account;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankNumber()
    {
        return $this->bank_number;
    }

    /**
     * @param mixed $bank_number
     *
     * @return $this
     */
    public function setBankNumber($bank_number)
    {
        $this->bank_number = $bank_number;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSwift()
    {
        return $this->swift;
    }

    /**
     * @param mixed $swift
     *
     * @return $this
     */
    public function setSwift($swift)
    {
        $this->swift = $swift;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @param mixed $iban
     *
     * @return $this
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCcType()
    {
        return $this->cc_type;
    }

    /**
     * @param mixed $cc_type
     *
     * @return $this
     */
    public function setCcType($cc_type)
    {
        $this->cc_type = $cc_type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCcNumber()
    {
        return $this->cc_number;
    }

    /**
     * @param mixed $cc_number
     *
     * @return $this
     */
    public function setCcNumber($cc_number)
    {
        $this->cc_number = $cc_number;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCcName()
    {
        return $this->cc_name;
    }

    /**
     * @param mixed $cc_name
     *
     * @return $this
     */
    public function setCcName($cc_name)
    {
        $this->cc_name = $cc_name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCcExpiration()
    {
        return $this->cc_expiration;
    }

    /**
     * @param mixed $cc_expiration
     *
     * @return $this
     */
    public function setCcExpiration($cc_expiration)
    {
        $this->cc_expiration = $cc_expiration;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReligion()
    {
        return $this->religion;
    }

    /**
     * @param mixed $religion
     *
     * @return $this
     */
    public function setReligion($religion)
    {
        $this->religion = $religion;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMarrigePlace()
    {
        return $this->marrigePlace;
    }

    /**
     * @param mixed $marrigePlace
     *
     * @return $this
     */
    public function setMarrigePlace($marrigePlace)
    {
        $this->marrigePlace = $marrigePlace;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMarrigeDate()
    {
        return $this->marrigeDate;
    }

    /**
     * @param mixed $marrigeDate
     *
     * @return $this
     */
    public function setMarrigeDate($marrigeDate)
    {
        $this->marrigeDate = $marrigeDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * @param mixed $newsletter
     *
     * @return $this
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewsletterFriends()
    {
        return $this->newsletterFriends;
    }

    /**
     * @param mixed $newsletterFriends
     *
     * @return $this
     */
    public function setNewsletterFriends($newsletterFriends)
    {
        $this->newsletterFriends = $newsletterFriends;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBackDoor()
    {
        return $this->backDoor;
    }

    /**
     * @param mixed $backDoor
     *
     * @return $this
     */
    public function setBackDoor($backDoor)
    {
        $this->backDoor = $backDoor;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFtpEnabled()
    {
        return $this->ftp_enabled;
    }

    /**
     * @param mixed $ftp_enabled
     *
     * @return $this
     */
    public function setFtpEnabled($ftp_enabled)
    {
        $this->ftp_enabled = $ftp_enabled;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFtpPassword()
    {
        return $this->ftp_password;
    }

    /**
     * @param mixed $ftp_password
     *
     * @return $this
     */
    public function setFtpPassword($ftp_password)
    {
        $this->ftp_password = $ftp_password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBalanceView()
    {
        return $this->balance_view;
    }

    /**
     * @param mixed $balance_view
     *
     * @return $this
     */
    public function setBalanceView($balance_view)
    {
        $this->balance_view = $balance_view;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeadPlace()
    {
        return $this->deadPlace;
    }

    /**
     * @param mixed $deadPlace
     *
     * @return $this
     */
    public function setDeadPlace($deadPlace)
    {
        $this->deadPlace = $deadPlace;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeadDeathdate()
    {
        return $this->deadDeathdate;
    }

    /**
     * @param mixed $deadDeathdate
     *
     * @return $this
     */
    public function setDeadDeathdate($deadDeathdate)
    {
        $this->deadDeathdate = $deadDeathdate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeadCemetery()
    {
        return $this->deadCemetery;
    }

    /**
     * @param mixed $deadCemetery
     *
     * @return $this
     */
    public function setDeadCemetery($deadCemetery)
    {
        $this->deadCemetery = $deadCemetery;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeadHide()
    {
        return $this->deadHide;
    }

    /**
     * @param mixed $deadHide
     *
     * @return $this
     */
    public function setDeadHide($deadHide)
    {
        $this->deadHide = $deadHide;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeadAutopsy()
    {
        return $this->deadAutopsy;
    }

    /**
     * @param mixed $deadAutopsy
     *
     * @return $this
     */
    public function setDeadAutopsy($deadAutopsy)
    {
        $this->deadAutopsy = $deadAutopsy;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeadCoronerPlace()
    {
        return $this->deadCoronerPlace;
    }

    /**
     * @param mixed $deadCoronerPlace
     *
     * @return $this
     */
    public function setDeadCoronerPlace($deadCoronerPlace)
    {
        $this->deadCoronerPlace = $deadCoronerPlace;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeadCoronerDate()
    {
        return $this->deadCoronerDate;
    }

    /**
     * @param mixed $deadCoronerDate
     *
     * @return $this
     */
    public function setDeadCoronerDate($deadCoronerDate)
    {
        $this->deadCoronerDate = $deadCoronerDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPartnerWienerVerein()
    {
        return $this->partnerWienerVerein;
    }

    /**
     * @param mixed $partnerWienerVerein
     *
     * @return $this
     */
    public function setPartnerWienerVerein($partnerWienerVerein)
    {
        $this->partnerWienerVerein = $partnerWienerVerein;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProminent()
    {
        return $this->prominent;
    }

    /**
     * @param mixed $prominent
     *
     * @return $this
     */
    public function setProminent($prominent)
    {
        $this->prominent = $prominent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExternalKey()
    {
        return $this->externalKey;
    }

    /**
     * @param mixed $externalKey
     *
     * @return $this
     */
    public function setExternalKey($externalKey)
    {
        $this->externalKey = $externalKey;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessagesView()
    {
        return $this->messages_view;
    }

    /**
     * @param mixed $messages_view
     *
     * @return $this
     */
    public function setMessagesView($messages_view)
    {
        $this->messages_view = $messages_view;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getXXXschreibzeichen()
    {
        return $this->XXXschreibzeichen;
    }

    /**
     * @param mixed $XXXschreibzeichen
     *
     * @return $this
     */
    public function setXXXschreibzeichen($XXXschreibzeichen)
    {
        $this->XXXschreibzeichen = $XXXschreibzeichen;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getXXXnationalitaet()
    {
        return $this->XXXnationalitaet;
    }

    /**
     * @param mixed $XXXnationalitaet
     *
     * @return $this
     */
    public function setXXXnationalitaet($XXXnationalitaet)
    {
        $this->XXXnationalitaet = $XXXnationalitaet;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getXXXdownload()
    {
        return $this->XXXdownload;
    }

    /**
     * @param mixed $XXXdownload
     *
     * @return $this
     */
    public function setXXXdownload($XXXdownload)
    {
        $this->XXXdownload = $XXXdownload;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getXXXlieferort()
    {
        return $this->XXXlieferort;
    }

    /**
     * @param mixed $XXXlieferort
     *
     * @return $this
     */
    public function setXXXlieferort($XXXlieferort)
    {
        $this->XXXlieferort = $XXXlieferort;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getXXXrechte()
    {
        return $this->XXXrechte;
    }

    /**
     * @param mixed $XXXrechte
     *
     * @return $this
     */
    public function setXXXrechte($XXXrechte)
    {
        $this->XXXrechte = $XXXrechte;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPremiumUntil()
    {
        return $this->premiumUntil;
    }

    /**
     * @param mixed $premiumUntil
     *
     * @return $this
     */
    public function setPremiumUntil($premiumUntil)
    {
        $this->premiumUntil = $premiumUntil;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * @param mixed $access
     *
     * @return $this
     */
    public function setAccess($access)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrettyUrl()
    {
        return $this->prettyUrl;
    }

    /**
     * @param mixed $prettyUrl
     *
     * @return $this
     */
    public function setPrettyUrl($prettyUrl)
    {
        $this->prettyUrl = $prettyUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImported()
    {
        return $this->imported;
    }

    /**
     * @param mixed $imported
     *
     * @return $this
     */
    public function setImported($imported)
    {
        $this->imported = $imported;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     *
     * @return $this
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param mixed $province
     *
     * @return $this
     */
    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }


}
