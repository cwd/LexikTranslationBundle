<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="es_cache", uniqueConstraints={@ORM\UniqueConstraint(name="KEY", columns={"key"})})
 *
 */
class Cache
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45, nullable=true, options={"default":"NULL"})
     */
    private $key;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $en;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $de;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $de_f;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $params_en;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $params_de;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $params_de_f;

    /**
     * @ORM\Column(type="timestamp", nullable=true, options={"default":"CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"})
     */
    private $timestamp;
}
