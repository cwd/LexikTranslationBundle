<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="es_bookProposal")
 */
class BookProposal
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $proposalId;

    /**
     * @ORM\Column(type="enum", nullable=false, options={"default":"'condolence'"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $text;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false, options={"default":"'0'"})
     */
    private $hide;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false, options={"default":"'0'"})
     */
    private $sort;
}