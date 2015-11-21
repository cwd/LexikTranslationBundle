<?php
namespace Aspetos\Model\Entity;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\ReminderHistoryRepository")
 */
class ReminderHistory
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $result;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $detail;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Reminder", inversedBy="reminderHistories")
     * @ORM\JoinColumn(name="reminderId", referencedColumnName="id", nullable=false)
     */
    private $reminder;

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
     * Set result
     *
     * @param string $result
     * @return ReminderHistory
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set detail
     *
     * @param string $detail
     * @return ReminderHistory
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set reminder
     *
     * @param \Aspetos\Model\Entity\Reminder $reminder
     * @return ReminderHistory
     */
    public function setReminder(\Aspetos\Model\Entity\Reminder $reminder)
    {
        $this->reminder = $reminder;

        return $this;
    }

    /**
     * Get reminder
     *
     * @return \Aspetos\Model\Entity\Reminder
     */
    public function getReminder()
    {
        return $this->reminder;
    }
}
