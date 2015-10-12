<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="es_deathnoticeDownload", indexes={@ORM\Index(name="uid", columns={"mid","uidLoggedIn","date"})})
 *
 */
class DeathnoticeDownload
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $mid;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $uidLoggedIn;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $date;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $downloaded;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $ip;

    /**
     * @ORM\Column(type="timestamp", nullable=false, options={"default":"CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"})
     */
    private $timestamp;
}
