<?php
namespace Aspetos\Model\Entity;
use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\MorticianRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 */
class Mortician extends Company
{
    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\MorticianAddress", mappedBy="mortician")
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Obituary", mappedBy="mortician")
     */
    private $obituaries;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Mortician", mappedBy="parentMortician")
     */
    private $morticians;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\MorticianMedia", mappedBy="mortician", cascade={"persist"})
     */
    private $medias;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Mortician", inversedBy="morticians")
     * @ORM\JoinColumn(name="parentMorticianId", referencedColumnName="id")
     */
    private $parentMortician;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Cemetery", inversedBy="morticians")
     * @ORM\JoinTable(
     *     name="MorticianHasCemetery",
     *     joinColumns={@ORM\JoinColumn(name="morticianId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="cemeteryId", referencedColumnName="id", nullable=false)}
     * )
     */
    private $cemeteries;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Supplier", inversedBy="mortician")
     * @ORM\JoinTable(
     *     name="MorticianHasSupplier",
     *     joinColumns={@ORM\JoinColumn(name="morticianId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="supplierId", referencedColumnName="id", nullable=false)}
     * )
     */
    private $supplier;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->obituaries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->morticians = new \Doctrine\Common\Collections\ArrayCollection();
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cemeteries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplier = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_MORTICIAN;
    }

    /**
     * Set address
     *
     * @param \Aspetos\Model\Entity\MorticianAddress $address
     * @return Mortician
     */
    public function setAddress(\Aspetos\Model\Entity\MorticianAddress $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \Aspetos\Model\Entity\MorticianAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add obituaries
     *
     * @param \Aspetos\Model\Entity\Obituary $obituaries
     * @return Mortician
     */
    public function addObituary(\Aspetos\Model\Entity\Obituary $obituaries)
    {
        $this->obituaries[] = $obituaries;

        return $this;
    }

    /**
     * Remove obituaries
     *
     * @param \Aspetos\Model\Entity\Obituary $obituaries
     */
    public function removeObituary(\Aspetos\Model\Entity\Obituary $obituaries)
    {
        $this->obituaries->removeElement($obituaries);
    }

    /**
     * Get obituaries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObituaries()
    {
        return $this->obituaries;
    }

    /**
     * Add morticians
     *
     * @param \Aspetos\Model\Entity\Mortician $morticians
     * @return Mortician
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

    /**
     * Add medias
     *
     * @param \Aspetos\Model\Entity\MorticianMedia $medias
     * @return Mortician
     */
    public function addMedia(\Aspetos\Model\Entity\MorticianMedia $medias)
    {
        $this->medias[] = $medias;

        return $this;
    }

    /**
     * Remove medias
     *
     * @param \Aspetos\Model\Entity\MorticianMedia $medias
     */
    public function removeMedia(\Aspetos\Model\Entity\MorticianMedia $medias)
    {
        $this->medias->removeElement($medias);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Set parentMortician
     *
     * @param \Aspetos\Model\Entity\Mortician $parentMortician
     * @return Mortician
     */
    public function setParentMortician(\Aspetos\Model\Entity\Mortician $parentMortician = null)
    {
        $this->parentMortician = $parentMortician;

        return $this;
    }

    /**
     * Get parentMortician
     *
     * @return \Aspetos\Model\Entity\Mortician
     */
    public function getParentMortician()
    {
        return $this->parentMortician;
    }

    /**
     * Add cemeteries
     *
     * @param \Aspetos\Model\Entity\Cemetery $cemeteries
     * @return Mortician
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
     * Add supplier
     *
     * @param \Aspetos\Model\Entity\Supplier $supplier
     * @return Mortician
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
}
