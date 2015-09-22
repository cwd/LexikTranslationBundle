<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="es_provisionCalculator")
 */
class ProvisionCalculator
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $age;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $pZD;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=3, options={"default":"NULL"})
     */
    private $pm3;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=3, options={"default":"NULL"})
     */
    private $pm5;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=3, options={"default":"NULL"})
     */
    private $pm7;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=3, options={"default":"NULL"})
     */
    private $pf3;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=3, options={"default":"NULL"})
     */
    private $pf5;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=3, options={"default":"NULL"})
     */
    private $pf7;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=3, options={"default":"NULL"})
     */
    private $sm3;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=3, options={"default":"NULL"})
     */
    private $sm5;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=3, options={"default":"NULL"})
     */
    private $sm7;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=3, options={"default":"NULL"})
     */
    private $sf3;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=3, options={"default":"NULL"})
     */
    private $sf5;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=3, options={"default":"NULL"})
     */
    private $sf7;
}