<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_deleteLog",
 *     indexes={@ORM\Index(name="foreign_id", columns={"foreign_id"}),@ORM\Index(name="uid", columns={"uid"})}
 * )
 *
 */
class DeleteLog
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=8)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $foreign_id;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true, options={"default":"NULL"})
     */
    private $uid;

    /**
     * @ORM\Column(type="string", length=200, nullable=true, options={"default":"NULL"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $usertype;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $table;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $data;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false, options={"default":"'1'"})
     */
    private $new;

    /**
     * @ORM\Column(type="timestamp", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $timestamp;
}
