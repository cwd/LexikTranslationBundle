<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\MorticianRepository")
 */
class Mortician
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true, length=250, nullable=false)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", length=30, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="integer", length=30, nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $webpage;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $vat;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $commercialRegNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $origMorticianId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $crmId;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\MorticianAddress", mappedBy="mortician")
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Obituary", mappedBy="mortician")
     */
    private $obituaries;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Mortician", mappedBy="parentMortician")
     */
    private $morticians;

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
}