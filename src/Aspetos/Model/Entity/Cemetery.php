<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CemeteryRepository")
 */
class Cemetery
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true, length=200, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $ownerName;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\CemeteryAddress", mappedBy="cemetery")
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Obituary", mappedBy="cemetery", cascade={"persist"})
     */
    private $obituary;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Region", inversedBy="cemetery")
     */
    private $region;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\CemeteryAdministration", inversedBy="cemeteries")
     * @ORM\JoinColumn(name="administrationId", referencedColumnName="id")
     */
    private $administration;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Supplier", inversedBy="cemetery")
     * @ORM\JoinTable(
     *     name="SupplierHasCemetery",
     *     joinColumns={@ORM\JoinColumn(name="cemeteryId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="supplierId", referencedColumnName="id", nullable=false)}
     * )
     */
    private $supplier;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Mortician", mappedBy="cemeteries")
     */
    private $morticians;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->obituary = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplier = new \Doctrine\Common\Collections\ArrayCollection();
        $this->morticians = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Cemetery
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

    /**
     * Set slug
     *
     * @param string $slug
     * @return Cemetery
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set ownerName
     *
     * @param string $ownerName
     * @return Cemetery
     */
    public function setOwnerName($ownerName)
    {
        $this->ownerName = $ownerName;

        return $this;
    }

    /**
     * Get ownerName
     *
     * @return string 
     */
    public function getOwnerName()
    {
        return $this->ownerName;
    }

    /**
     * Set address
     *
     * @param \Aspetos\Model\Entity\CemeteryAddress $address
     * @return Cemetery
     */
    public function setAddress(\Aspetos\Model\Entity\CemeteryAddress $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \Aspetos\Model\Entity\CemeteryAddress 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add obituary
     *
     * @param \Aspetos\Model\Entity\Obituary $obituary
     * @return Cemetery
     */
    public function addObituary(\Aspetos\Model\Entity\Obituary $obituary)
    {
        $this->obituary[] = $obituary;

        return $this;
    }

    /**
     * Remove obituary
     *
     * @param \Aspetos\Model\Entity\Obituary $obituary
     */
    public function removeObituary(\Aspetos\Model\Entity\Obituary $obituary)
    {
        $this->obituary->removeElement($obituary);
    }

    /**
     * Get obituary
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getObituary()
    {
        return $this->obituary;
    }

    /**
     * Set region
     *
     * @param \Aspetos\Model\Entity\Region $region
     * @return Cemetery
     */
    public function setRegion(\Aspetos\Model\Entity\Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \Aspetos\Model\Entity\Region 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set administration
     *
     * @param \Aspetos\Model\Entity\CemeteryAdministration $administration
     * @return Cemetery
     */
    public function setAdministration(\Aspetos\Model\Entity\CemeteryAdministration $administration = null)
    {
        $this->administration = $administration;

        return $this;
    }

    /**
     * Get administration
     *
     * @return \Aspetos\Model\Entity\CemeteryAdministration 
     */
    public function getAdministration()
    {
        return $this->administration;
    }

    /**
     * Add supplier
     *
     * @param \Aspetos\Model\Entity\Supplier $supplier
     * @return Cemetery
     */
    public function addSupplier(\Aspetos\Model\Entity\Supplier $supplier)
    {
        $this->supplier[] = $supplier;

        return $this;
    }

    /**
     * Remove supplier
     *
     * @param \Aspetos\Model\Entity\Supplier $supplier
     */
    public function removeSupplier(\Aspetos\Model\Entity\Supplier $supplier)
    {
        $this->supplier->removeElement($supplier);
    }

    /**
     * Get supplier
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Add morticians
     *
     * @param \Aspetos\Model\Entity\Mortician $morticians
     * @return Cemetery
     */
    public function addMortician(\Aspetos\Model\Entity\Mortician $morticians)
    {
        $this->morticians[] = $morticians;

        return $this;
    }

    /**
     * Remove morticians
     *
     * @param \Aspetos\Model\Entity\Mortician $morticians
     */
    public function removeMortician(\Aspetos\Model\Entity\Mortician $morticians)
    {
        $this->morticians->removeElement($morticians);
    }

    /**
     * Get morticians
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMorticians()
    {
        return $this->morticians;
    }
}
