<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_user_comment",
 *     indexes={@ORM\Index(name="from", columns={"from"}),@ORM\Index(name="for", columns={"to"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="from_2", columns={"from","to"})}
 * )
 *
 */
class UserComment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $cid;

    /**
     * @ORM\Column(type="integer", length=2, nullable=false)
     */
    private $star;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $personalValue;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $body;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $date;
}
