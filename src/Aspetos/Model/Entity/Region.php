<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class Region
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
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Cemetery", mappedBy="region")
     */
    private $cemetery;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Country", inversedBy="region")
     * @ORM\JoinColumn(name="countryId", referencedColumnName="id", nullable=false)
     */
    private $country;
}