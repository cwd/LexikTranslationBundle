<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CandleRepository")
 */
class Candle
{
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
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\User")
     * @ORM\JoinColumn(name="createdBy", referencedColumnName="id", nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Obituary", inversedBy="candles")
     * @ORM\JoinColumn(name="obituaryId", referencedColumnName="id", nullable=false)
     */
    private $obituary;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\OrderItem")
     * @ORM\JoinColumn(name="orderItemId", referencedColumnName="id", nullable=false)
     */
    private $orderItem;

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
     * @param \Aspetos\Model\Entity\User $createdBy
     * @return Candle
     */
    public function setCreatedBy(\Aspetos\Model\Entity\User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Aspetos\Model\Entity\User 
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
}
