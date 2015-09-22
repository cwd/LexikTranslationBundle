<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_menu",
 *     indexes={
 *         @ORM\Index(name="componentid", columns={"menutype","published"}),
 *         @ORM\Index(name="menutype", columns={"menutype"}),
 *         @ORM\Index(name="name_lang", columns={"name_lang"})
 *     },
 *     uniqueConstraints={@ORM\UniqueConstraint(name="prettyUrl", columns={"prettyUrl","domain"})}
 * )
 */
class Menu
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $menuid;

    /**
     * @ORM\Column(type="enum", nullable=false)
     */
    private $domain;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $menutype;

    /**
     * @ORM\Column(type="string", length=200, nullable=true, options={"default":"NULL"})
     */
    private $area;

    /**
     * @ORM\Column(type="enum", nullable=false)
     */
    private $permission;

    /**
     * @ORM\Column(type="string", length=200, nullable=true, options={"default":"NULL"})
     */
    private $image;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $link;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false, options={"default":"'0'"})
     */
    private $published;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false, options={"default":"'0'"})
     */
    private $hideInMenu;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $externalLink;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false, options={"default":"'0'"})
     */
    private $parent;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"'0'"})
     */
    private $sort;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $prettyUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $prettyUrlSpecial;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $metaKeyword;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $metaDescription;
}