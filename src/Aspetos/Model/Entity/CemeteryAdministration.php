<?php
namespace Aspetos\Model\Entity;

use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CemeteryAdministrationRepository")
 */
class CemeteryAdministration extends \Aspetos\Model\Entity\Address
{
    use Timestampable;
    use Blameable;

    /**
     * @ORM\Column(type="phone_number", nullable=true)
     * @AssertPhoneNumber(groups={"default"})
     */
    private $phone;

    /**
     * @ORM\Column(type="phone_number", nullable=true)
     * @AssertPhoneNumber(groups={"default"})
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     * @Assert\Email(groups={"default"})
     * @Assert\Length(max = "75", groups={"default"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\Url(groups={"default"})
     * @Assert\Length(max = "75", groups={"default"})
     */
    private $webpage;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\Length(max = "200", groups={"default"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Cemetery", mappedBy="administration", cascade={"persist"})
     */
    private $cemeteries;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cemeteries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return CemeteryAdministration
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return CemeteryAdministration
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return CemeteryAdministration
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set webpage
     *
     * @param string $webpage
     * @return CemeteryAdministration
     */
    public function setWebpage($webpage)
    {
        $this->webpage = $webpage;

        return $this;
    }

    /**
     * Get webpage
     *
     * @return string
     */
    public function getWebpage()
    {
        return $this->webpage;
    }

    /**
     * Add cemeteries
     *
     * @param \Aspetos\Model\Entity\Cemetery $cemeteries
     * @return CemeteryAdministration
     */
    public function addCemetery(\Aspetos\Model\Entity\Cemetery $cemeteries)
    {
        $this->cemeteries[] = $cemeteries;

        return $this;
    }

    /**
     * Remove cemeteries
     *
     * @param \Aspetos\Model\Entity\Cemetery $cemeteries
     */
    public function removeCemetery(\Aspetos\Model\Entity\Cemetery $cemeteries)
    {
        $this->cemeteries->removeElement($cemeteries);
    }

    /**
     * Get cemeteries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCemeteries()
    {
        return $this->cemeteries;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CemeteryAdministration
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
