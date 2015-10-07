<?php
namespace Aspetos\Model\Entity;
use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\ObituaryEventRepository")
 */
class ObituaryEvent
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
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateStart;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\ObituaryEventType", inversedBy="obituaryEvent")
     * @ORM\JoinColumn(name="typeId", referencedColumnName="id", nullable=false)
     */
    private $obituaryEventType;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Obituary", inversedBy="events")
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
     * Set dateStart
     *
     * @param \DateTime $dateStart
     * @return ObituaryEvent
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ObituaryEvent
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return ObituaryEvent
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set obituaryEventType
     *
     * @param \Aspetos\Model\Entity\ObituaryEventType $obituaryEventType
     * @return ObituaryEvent
     */
    public function setObituaryEventType(\Aspetos\Model\Entity\ObituaryEventType $obituaryEventType)
    {
        $this->obituaryEventType = $obituaryEventType;

        return $this;
    }

    /**
     * Get obituaryEventType
     *
     * @return \Aspetos\Model\Entity\ObituaryEventType
     */
    public function getObituaryEventType()
    {
        return $this->obituaryEventType;
    }

    /**
     * Set obituary
     *
     * @param \Aspetos\Model\Entity\Obituary $obituary
     * @return ObituaryEvent
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
}
