<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_robotMail",
 *     indexes={@ORM\Index(name="status", columns={"status"})},
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="directory", columns={"directory"}),
 *         @ORM\UniqueConstraint(name="messageId", columns={"messageId"})
 *     }
 * )
 */
class RobotMail
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5, nullable=true, options={"default":"NULL"})
     */
    private $domain;

    /**
     * @ORM\Column(type="string", length=200, nullable=true, options={"default":"NULL"})
     */
    private $messageId;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $directory;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $from;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $to;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $subject;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $dateTime;

    /**
     * @ORM\Column(type="enum", nullable=false, options={"default":"'fresh'"})
     */
    private $status;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $log;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $created;

    /**
     * @ORM\Column(type="timestamp", nullable=false, options={"default":"CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"})
     */
    private $timestamp;
}