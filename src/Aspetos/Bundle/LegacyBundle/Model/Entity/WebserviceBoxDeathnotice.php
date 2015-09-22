<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_webservice_boxDeathnotice",
 *     indexes={
 *         @ORM\Index(name="code", columns={"code"}),
 *         @ORM\Index(name="uid", columns={"uid"}),
 *         @ORM\Index(name="uidForeign", columns={"uid"})
 *     }
 * )
 */
class WebserviceBoxDeathnotice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $heading;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $noticeType;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $template;

    /**
     * @ORM\Column(type="integer", length=1, nullable=true, options={"default":"NULL"})
     */
    private $rss;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $restriction;

    /**
     * @ORM\Column(type="string", length=16, nullable=true, options={"default":"'UTF-8'"})
     */
    private $charset;

    /**
     * @ORM\Column(type="string", length=10, nullable=true, options={"default":"NULL"})
     */
    private $code;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $internalComment;
}