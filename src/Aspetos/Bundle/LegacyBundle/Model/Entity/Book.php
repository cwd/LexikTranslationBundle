<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="es_book", indexes={@ORM\Index(name="uid", columns={"uid"})})
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $bookId;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false, options={"default":"'0'"})
     */
    private $hide;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $uid;

    /**
     * @return mixed
     */
    public function getBookId()
    {
        return $this->bookId;
    }

    /**
     * @param mixed $bookId
     *
     * @return $this
     */
    public function setBookId($bookId)
    {
        $this->bookId = $bookId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHide()
    {
        return $this->hide;
    }

    /**
     * @param mixed $hide
     *
     * @return $this
     */
    public function setHide($hide)
    {
        $this->hide = $hide;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     *
     * @return $this
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }


}
