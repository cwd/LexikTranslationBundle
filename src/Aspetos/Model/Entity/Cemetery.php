<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
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
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\CemetryAdministration", inversedBy="cemeteries")
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
}