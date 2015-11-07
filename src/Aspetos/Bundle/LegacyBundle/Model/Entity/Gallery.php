<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true, repositoryClass="Aspetos\Bundle\LegacyBundle\Model\Repository\GalleryRepository")
 * @ORM\Table(
 *     name="es_gallery",
 *     indexes={@ORM\Index(name="es_gallery_ibfk_1", columns={"uid"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="uid_gid_sort", columns={"uid","gtype","sort"})}
 * )
 *
 */
class Gallery
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $gid;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $uid;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $gtype;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false, options={"default":"'0'"})
     */
    private $sort;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $hide;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=false, scale=2, options={"default":"'1.00'"})
     */
    private $priceFactor;

    /**
     * @ORM\Column(type="string", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $timestamp;

    /**
     * @return mixed
     */
    public function getGid()
    {
        return $this->gid;
    }

    /**
     * @return mixed
     */
    public function getGtype()
    {
        return $this->gtype;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @return mixed
     */
    public function getHide()
    {
        return $this->hide;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getPriceFactor()
    {
        return $this->priceFactor;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }
}
