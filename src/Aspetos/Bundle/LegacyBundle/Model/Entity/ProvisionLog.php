<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="es_provisionLog")
 */
class ProvisionLog
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $provisionId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $forename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $surname;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $birthyear;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $gender;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $insuranceSum;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $payment;

    /**
     * @ORM\Column(type="float", length=12, nullable=true, scale=3, options={"default":"NULL"})
     */
    private $premium;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $street;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $zip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $place;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $phone;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $phoneReachable;

    /**
     * @ORM\Column(type="string", length=100, nullable=true, options={"default":"NULL"})
     */
    private $email;

    /**
     * @ORM\Column(type="date", nullable=true, options={"default":"NULL"})
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $comment;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $consultation;

    /**
     * @ORM\Column(type="string", length=45, nullable=true, options={"default":"NULL"})
     */
    private $ip;

    /**
     * @ORM\Column(type="timestamp", nullable=true, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $timestamp;
}