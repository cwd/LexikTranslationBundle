<?php
namespace Aspetos\Model\Entity;
use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use KPhoen\DoctrineStateMachineBehavior\Entity\Stateful;
use KPhoen\DoctrineStateMachineBehavior\Entity\StatefulTrait;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CondolenceRepository")
 * @ORM\Table(
 *     indexes={
 *         @ORM\Index(name="IDX_Condolence_OrigId", columns={"origId"}),
 *         @ORM\Index(name="IDX_state", columns={"state"}),
 *         @ORM\Index(name="IDX_public", columns={"public"}),
 *         @ORM\Index(name="IDX_search", columns={"id","public","deletedAt","state"})
 *     }
 * )
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 */
class Condolence implements Stateful
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
     * @ORM\Column(type="text", nullable=false)
     */
    private $content;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":1})
     */
    private $public;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="string", length=100, nullable=false, options={"default":"inactive"})
     */
    private $state;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $origId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $fromName;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Obituary", inversedBy="condolences")
     * @ORM\JoinColumn(name="obituaryId", referencedColumnName="id", nullable=false)
     */
    private $obituary;

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
     * Set content
     *
     * @param string $content
     * @return Condolence
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
     * Set public
     *
     * @param boolean $public
     * @return Condolence
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set obituary
     *
     * @param \Aspetos\Model\Entity\Obituary $obituary
     * @return Condolence
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
     * @return bool
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param bool $state
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrigId()
    {
        return $this->origId;
    }

    /**
     * @param int $origId
     *
     * @return $this
     */
    public function setOrigId($origId)
    {
        $this->origId = $origId;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @param string $fromName
     *
     * @return $this
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;

        return $this;
    }
}
