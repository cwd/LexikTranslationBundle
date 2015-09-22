<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_message",
 *     indexes={@ORM\Index(name="from", columns={"from"}),@ORM\Index(name="to", columns={"to"})}
 * )
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $messageid;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    private $subject;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $body;

    /**
     * @ORM\Column(type="timestamp", nullable=false, options={"default":"CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"})
     */
    private $date;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false)
     */
    private $new;
}