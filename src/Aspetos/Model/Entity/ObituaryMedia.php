<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class ObituaryMedia
{
    const TYPE_OBITUARY = 1;
    const TYPE_PORTRAIT = 2;
    const TYPE_THANKYOU = 3;
    const TYPE_MOURNING = 4;
    const TYPE_DEATHNOTICE1 = 5;
    const TYPE_DEATHNOTICE2 = 6;
    const TYPE_DEATHNOTICE3 = 7;
    const TYPE_ANNIVERSARY1 = 8;
    const TYPE_ANNIVERSARY2 = 9;
    const TYPE_ANNIVERSARY3 = 10;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=2, nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $origId;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Media")
     * @ORM\JoinColumn(name="mediaId", referencedColumnName="id", nullable=false)
     */
    private $media;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Obituary", inversedBy="medias")
     * @ORM\JoinColumn(name="obituaryId", referencedColumnName="id", nullable=false)
     */
    private $obituary;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return ObituaryMedia
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set media
     *
     * @param \Aspetos\Model\Entity\Media $media
     * @return ObituaryMedia
     */
    public function setMedia(\Aspetos\Model\Entity\Media $media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Aspetos\Model\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set obituary
     *
     * @param \Aspetos\Model\Entity\Obituary $obituary
     * @return ObituaryMedia
     */
    public function setObituary(\Aspetos\Model\Entity\Obituary $obituary)
    {
        $this->obituary = $obituary;

        return $this;
    }

    /**
     * Get obituary
     *
     * @return \Aspetos\Model\Entity\Obituary
     */
    public function getObituary()
    {
        return $this->obituary;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrigId()
    {
        return $this->origId;
    }

    /**
     * @param mixed $origId
     *
     * @return $this
     */
    public function setOrigId($origId)
    {
        $this->origId = $origId;

        return $this;
    }
}
