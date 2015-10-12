<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="es_translation_system", uniqueConstraints={@ORM\UniqueConstraint(name="key", columns={"key"})})
 *
 */
class TranslationSystem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $key;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $en;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $de;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $de_f;
}
