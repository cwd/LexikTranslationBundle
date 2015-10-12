<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="es_exception")
 *
 */
class Exception
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $class;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $code;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $file;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $line;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $traceAsString;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $trace;

    /**
     * @ORM\Column(type="timestamp", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created;
}
