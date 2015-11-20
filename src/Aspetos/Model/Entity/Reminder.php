<?php
namespace Aspetos\Model\Entity;
use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use KPhoen\DoctrineStateMachineBehavior\Entity\Stateful;
use KPhoen\DoctrineStateMachineBehavior\Entity\StatefulTrait;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\ReminderRepository")
 * @ORM\Table(
 *     indexes={@ORM\Index(name="IDX_REMINDER_TYPE_STATE", columns={"type","state"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="UQ_EMAIL_OBITUARY", columns={"email","obituaryId"})}
 * )
 */
class Reminder implements Stateful
{
    use Timestampable;
    use Blameable;
    use StatefulTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $state;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $remindAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $origId;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\ReminderHistory", mappedBy="reminder", cascade={"persist"})
     */
    private $reminderHistories;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Obituary", inversedBy="reminders")
     * @ORM\JoinColumn(name="obituaryId", referencedColumnName="id", nullable=false)
     */
    private $obituary;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reminderHistories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Sets the object state.
     * Used by the StateMachine behavior
     *
     * @return string
     */
    public function getFiniteState()
    {
        return $this->getState();
    }

    /**
     * Sets the object state.
     * Used by the StateMachine behavior
     *
     * @param string $state
     * @return Company
     */
    public function setFiniteState($state)
    {
        return $this->setState($state);
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
     * Set type
     *
     * @param string $type
     * @return Reminder
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Reminder
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Reminder
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set remindAt
     *
     * @param \DateTime $remindAt
     * @return Reminder
     */
    public function setRemindAt($remindAt)
    {
        $this->remindAt = $remindAt;

        return $this;
    }

    /**
     * Get remindAt
     *
     * @return \DateTime
     */
    public function getRemindAt()
    {
        return $this->remindAt;
    }

    /**
     * Set origId
     *
     * @param integer $origId
     * @return Reminder
     */
    public function setOrigId($origId)
    {
        $this->origId = $origId;

        return $this;
    }

    /**
     * Get origId
     *
     * @return integer
     */
    public function getOrigId()
    {
        return $this->origId;
    }

    /**
     * Add reminderHistories
     *
     * @param \Aspetos\Model\Entity\ReminderHistory $reminderHistories
     * @return Reminder
     */
    public function addReminderHistory(\Aspetos\Model\Entity\ReminderHistory $reminderHistories)
    {
        $this->reminderHistories[] = $reminderHistories;

        return $this;
    }

    /**
     * Remove reminderHistories
     *
     * @param \Aspetos\Model\Entity\ReminderHistory $reminderHistories
     */
    public function removeReminderHistory(\Aspetos\Model\Entity\ReminderHistory $reminderHistories)
    {
        $this->reminderHistories->removeElement($reminderHistories);
    }

    /**
     * Get reminderHistories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReminderHistories()
    {
        return $this->reminderHistories;
    }

    /**
     * Set obituary
     *
     * @param \Aspetos\Model\Entity\Obituary $obituary
     * @return Reminder
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
