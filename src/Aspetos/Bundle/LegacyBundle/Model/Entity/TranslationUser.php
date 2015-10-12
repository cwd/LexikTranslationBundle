<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="es_translation_user")
 *
 */
class TranslationUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $tid;

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
