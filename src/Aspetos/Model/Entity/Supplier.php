<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\SupplierRepository")
 */
class Supplier
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $crmId;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\SupplierAddress", mappedBy="supplier")
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Supplier", mappedBy="parentSupplier")
     */
    private $suppliers;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Product", mappedBy="supplier")
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\BasePrice", mappedBy="supplier")
     */
    private $basePrices;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Supplier", inversedBy="suppliers")
     * @ORM\JoinColumn(name="parentSupplierId", referencedColumnName="id")
     */
    private $parentSupplier;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Obituary", inversedBy="suppliers", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="ObituaryHasSupplier",
     *     joinColumns={@ORM\JoinColumn(name="supplierId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="obituaryId", referencedColumnName="id", nullable=false)}
     * )
     */
    private $obituaries;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\SupplierType", inversedBy="supplier")
     * @ORM\JoinTable(
     *     name="SupplierHasType",
     *     joinColumns={@ORM\JoinColumn(name="supplierId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="supplierTypeId", referencedColumnName="id", nullable=false)}
     * )
     */
    private $supplierType;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Cemetery", mappedBy="supplier")
     */
    private $cemetery;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Mortician", mappedBy="supplier")
     */
    private $mortician;
}