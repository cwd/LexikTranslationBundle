<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\ObituaryEventTypeRepository")
 */
class ObituaryEventType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Aspetos\Model\Entity\ObituaryEvent",
     *     mappedBy="obituaryEventType",
     *     cascade={"persist"}
     * )
     */
    private $obituaryEvent;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->obituaryEvent = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return ObituaryEventType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add obituaryEvent
     *
     * @param \Aspetos\Model\Entity\ObituaryEvent $obituaryEvent
     * @return ObituaryEventType
     */
    public function addObituaryEvent(\Aspetos\Model\Entity\ObituaryEvent $obituaryEvent)
    {
        $this->obituaryEvent[] = $obituaryEvent;

        return $this;
    }

    /**
     * Remove obituaryEvent
     *
     * @param \Aspetos\Model\Entity\ObituaryEvent $obituaryEvent
     */
    public function removeObituaryEvent(\Aspetos\Model\Entity\ObituaryEvent $obituaryEvent)
    {
        $this->obituaryEvent->removeElement($obituaryEvent);
    }

    /**
     * Get obituaryEvent
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getObituaryEvent()
    {
        return $this->obituaryEvent;
    }
}
