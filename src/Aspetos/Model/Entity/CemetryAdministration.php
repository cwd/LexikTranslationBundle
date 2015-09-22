<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class CemetryAdministration extends \Aspetos\Model\Entity\Address
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
}