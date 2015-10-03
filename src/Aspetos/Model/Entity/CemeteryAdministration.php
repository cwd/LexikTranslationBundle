<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CemeteryAdministrationRepository")
 */
class CemeteryAdministration extends \Aspetos\Model\Entity\Address
{
    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $webpage;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Cemetery", mappedBy="administration")
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
}
