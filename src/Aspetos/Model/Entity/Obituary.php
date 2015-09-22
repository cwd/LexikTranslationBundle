<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\ObituaryRepository")
 */
class Obituary
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $titlePrefix;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $titlePostfix;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $bornAs;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dayOfBirth;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $dayOfDeath;

    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\ObituaryEvent", mappedBy="obituary")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Condolence", mappedBy="obituary", cascade={"persist"})
     */
    private $condolences;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Candle", mappedBy="obituary")
     */
    private $candles;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Cemetery", inversedBy="obituary")
     * @ORM\JoinColumn(name="cemeteryId", referencedColumnName="id", nullable=false)
     */
    private $cemetery;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Mortician", inversedBy="obituaries")
     * @ORM\JoinColumn(name="morticianId", referencedColumnName="id", nullable=false)
     */
    private $mortician;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Supplier", mappedBy="obituaries")
     */
    private $suppliers;
}