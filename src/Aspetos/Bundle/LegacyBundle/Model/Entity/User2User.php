<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_user2es_user",
 *     indexes={
 *         @ORM\Index(name="uid2user", columns={"uid"}),
 *         @ORM\Index(name="uidTo2user", columns={"uidTo"}),
 *         @ORM\Index(name="type", columns={"type"})
 *     },
 *     uniqueConstraints={@ORM\UniqueConstraint(name="uniqueIndex", columns={"uid","type","uidTo"})}
 * )
 *
 */
class User2User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $linkId;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $uid;

    /**
     * @ORM\Column(type="integer")
     */
    private $uidTo;

    /**
     * @return mixed
     */
    public function getLinkId()
    {
        return $this->linkId;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return mixed
     */
    public function getUidTo()
    {
        return $this->uidTo;
    }


}
