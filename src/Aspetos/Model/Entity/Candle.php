<?php
namespace Aspetos\Model\Entity;
use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CandleRepository")
 * @ORM\Table(indexes={@ORM\Index(name="IDX_Candle_OrigId", columns={"origId"})})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 */
class Candle
{
    use Timestampable;
    use Blameable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $expiresAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":1})
     */
    private $state;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $origId;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Obituary", inversedBy="candles")
     * @ORM\JoinColumn(name="obituaryId", referencedColumnName="id", nullable=false)
     */
    private $obituary;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\OrderItem")
     * @ORM\JoinColumn(name="orderItemId", referencedColumnName="id")
     */
    private $orderItem;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Product")
     * @ORM\JoinColumn(name="productId", referencedColumnName="id")
     */
    private $product;

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
     * Set content
     *
     * @param string $content
     * @return Candle
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set expiresAt
     *
     * @param \DateTime $expiresAt
     * @return Candle
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Set createdBy
     *
     * @param \Aspetos\Model\Entity\BaseUser $createdBy
     * @return Candle
     */
    public function setCreatedBy(\Aspetos\Model\Entity\BaseUser $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Aspetos\Model\Entity\BaseUser
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set obituary
     *
     * @param \Aspetos\Model\Entity\Obituary $obituary
     * @return Candle
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
     * Set orderItem
     *
     * @param \Aspetos\Model\Entity\OrderItem $orderItem
     * @return Candle
     */
    public function setOrderItem(\Aspetos\Model\Entity\OrderItem $orderItem)
    {
        $this->orderItem = $orderItem;

        return $this;
    }

    /**
     * Get orderItem
     *
     * @return \Aspetos\Model\Entity\OrderItem
     */
    public function getOrderItem()
    {
        return $this->orderItem;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     *
     * @return $this
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

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

    /**
     * Set product
     *
     * @param \Aspetos\Model\Entity\Product $product
     * @return Candle
     */
    public function setProduct(\Aspetos\Model\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Aspetos\Model\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
