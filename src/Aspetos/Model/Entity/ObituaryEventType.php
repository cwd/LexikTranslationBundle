<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
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
}