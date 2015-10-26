<?php
namespace Aspetos\Model\Entity;

use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CemeteryRepository")
 */
class Cemetery
{
    use Timestampable;
    use Blameable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(groups={"default"}, max = 250)
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true, length=200, nullable=false)
     * @Assert\Length(groups={"default"}, max = 200)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\Length(groups={"default"}, max = 200)
     */
    private $ownerName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\CemeteryAddress", mappedBy="cemetery", cascade={"persist"})
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Obituary", mappedBy="cemetery", cascade={"persist"})
     */
    private $obituary;

    /**
     * 
     */
    private $region;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Aspetos\Model\Entity\CemeteryAdministration",
     *     inversedBy="cemeteries",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="administrationId", referencedColumnName="id")
     */
    private $administration;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Supplier", inversedBy="cemetery")
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
     * Set id
     * @param integer $id
     * @return integer
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
    public function setAddress(CemeteryAddress $address = null)
    {
        $this->address = $address;
        $address->setCemetery($this);

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
    public function addObituary(Obituary $obituary)
    {
        $this->obituary[] = $obituary;

        return $this;
    }

    /**
     * Remove obituary
     *
     * @param \Aspetos\Model\Entity\Obituary $obituary
     */
    public function removeObituary(Obituary $obituary)
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
    public function setRegion(Region $region = null)
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
    public function setAdministration(CemeteryAdministration $administration = null)
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
    public function addSupplier(Supplier $supplier)
    {
        $this->supplier[] = $supplier;

        return $this;
    }

    /**
     * Remove supplier
     *
     * @param \Aspetos\Model\Entity\Supplier $supplier
     */
    public function removeSupplier(Supplier $supplier)
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
    public function addMortician(Mortician $morticians)
    {
        $this->morticians[] = $morticians;

        return $this;
    }

    /**
     * Remove morticians
     *
     * @param \Aspetos\Model\Entity\Mortician $morticians
     */
    public function removeMortician(Mortician $morticians)
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

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     *
     * @return $this
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
