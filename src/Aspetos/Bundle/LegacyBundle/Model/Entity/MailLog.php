<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="es_mailLog")
 */
class MailLog
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45, nullable=true, options={"default":"NULL"})
     */
    private $type;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $uidLoggedIn;

    /**
     * @ORM\Column(type="string", length=20, nullable=true, options={"default":"NULL"})
     */
    private $ip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $from;

    /**
     * @ORM\Column(type="string", length=45, nullable=true, options={"default":"NULL"})
     */
    private $to;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $subject;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $msg;

    /**
     * @ORM\Column(type="timestamp", nullable=true, options={"default":"NULL"})
     */
    private $datetime;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $status;
}