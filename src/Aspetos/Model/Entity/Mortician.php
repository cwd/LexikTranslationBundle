<?php
namespace Aspetos\Model\Entity;
use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\MorticianRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 */
class Mortician extends Company
{
    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\MorticianAddress", mappedBy="mortician", cascade={"persist"})
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Obituary", mappedBy="mortician", cascade={"persist"})
     */
    private $obituaries;

    /**
     *
     @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\MorticianUser", mappedBy="mortician")*/
    private $users;

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
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Supplier", inversedBy="morticians")
     * @ORM\JoinTable(
     *     name="MorticianHasSupplier",
     *     joinColumns={@ORM\JoinColumn(name="morticianId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="supplierId", referencedColumnName="id", nullable=false)}
     * )
     */
    private $suppliers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->obituaries = new ArrayCollection();
        $this->morticians = new ArrayCollection();
        $this->cemeteries = new ArrayCollection();
        $this->supplier = new ArrayCollection();
        parent::__construct();
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
     * @param MorticianAddress $address
     * @return Mortician
     */
    public function setAddress(MorticianAddress $address = null)
    {
        $this->address = $address;
        $address->setMortician($this);

        return $this;
    }

    /**
     * Get address
     *
     * @return MorticianAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add obituaries
     *
     * @param Obituary $obituaries
     * @return Mortician
     */
    public function addObituary(Obituary $obituaries)
    {
        $this->obituaries[] = $obituaries;

        return $this;
    }

    /**
     * Remove obituaries
     *
     * @param Obituary $obituaries
     */
    public function removeObituary(Obituary $obituaries)
    {
        $this->obituaries->removeElement($obituaries);
    }

    /**
     * Get obituaries
     *
     * @return Collection
     */
    public function getObituaries()
    {
        return $this->obituaries;
    }

    /**
     * Add morticians
     *
     * @param Mortician $morticians
     * @return Mortician
     */
    public function addMortician(Mortician $morticians)
    {
        $this->morticians[] = $morticians;

        return $this;
    }

    /**
     * Remove morticians
     *
     * @param Mortician $morticians
     */
    public function removeMortician(Mortician $morticians)
    {
        $this->morticians->removeElement($morticians);
    }

    /**
     * Get morticians
     *
     * @return Collection
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
     * @param Mortician $parentMortician
     * @return Mortician
     */
    public function setParentMortician(Mortician $parentMortician = null)
    {
        $this->parentMortician = $parentMortician;

        return $this;
    }

    /**
     * Get parentMortician
     *
     * @return Mortician
     */
    public function getParentMortician()
    {
        return $this->parentMortician;
    }

    /**
     * Add cemeteries
     *
     * @param Cemetery $cemeteries
     * @return Mortician
     */
    public function addCemetery(Cemetery $cemeteries)
    {
        if (!$this->getCemeteries()->contains($cemeteries)) {
            $this->cemeteries[] = $cemeteries;
        }

        return $this;
    }

    /**
     * Remove cemeteries
     *
     * @param Cemetery $cemeteries
     */
    public function removeCemetery(Cemetery $cemeteries)
    {
        $this->cemeteries->removeElement($cemeteries);
    }

    /**
     * Get cemeteries
     *
     * @return Collection
     */
    public function getCemeteries()
    {
        return $this->cemeteries;
    }

    /**
     * Add supplier
     *
     * @param Supplier $supplier
     * @return Mortician
     */
    public function addSupplier(Supplier $supplier)
    {
        if (!$this->getSuppliers()->contains($supplier)) {
            $this->suppliers[] = $supplier;
        }

        return $this;
    }

    /**
     * Remove supplier
     *
     * @param Supplier $supplier
     */
    public function removeSupplier(Supplier $supplier)
    {
        $this->suppliers->removeElement($supplier);
    }

    /**
     * Get supplier
     *
     * @return Collection
     */
    public function getSuppliers()
    {
        return $this->suppliers;
    }

    /**
     * Add users
     *
     * @param \Aspetos\Model\Entity\MorticianUser $users
     * @return Mortician
     */
    public function addUser(\Aspetos\Model\Entity\MorticianUser $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Aspetos\Model\Entity\MorticianUser $users
     */
    public function removeUser(\Aspetos\Model\Entity\MorticianUser $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * get formatted name for select in backend
     * @return string
     */
    public function formattedName()
    {
        return sprintf('%s (%s-%s %s)', $this->getName(), $this->getAddress()->getCountry(), $this->getAddress()->getZipcode(), $this->getAddress()->getCity());
    }
}
