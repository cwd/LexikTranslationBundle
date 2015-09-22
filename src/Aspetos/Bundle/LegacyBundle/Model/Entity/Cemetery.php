<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="es_cemetery", uniqueConstraints={@ORM\UniqueConstraint(name="prettyUrl", columns={"prettyUrl"})})
 */
class Cemetery
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $cemId;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $idOldSystem;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $province;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $district;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=20, nullable=true, options={"default":"NULL"})
     */
    private $zip;

    /**
     * @ORM\Column(type="string", length=150, nullable=true, options={"default":"NULL"})
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
     * @ORM\Column(type="integer", length=1, nullable=true, options={"default":"NULL"})
     */
    private $geoManual;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $administrationName;

    /**
     * @ORM\Column(type="string", length=150, nullable=true, options={"default":"NULL"})
     */
    private $administrationPlace;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $administrationStreet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $administrationPhone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $ownerName;

    /**
     * @ORM\Column(type="string", length=20, nullable=true, options={"default":"NULL"})
     */
    private $administrationZip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $prettyUrl;
}