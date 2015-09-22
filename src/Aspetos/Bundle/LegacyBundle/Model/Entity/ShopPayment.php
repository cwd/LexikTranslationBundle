<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="es_shop_payment", indexes={@ORM\Index(name="name_lang", columns={"name_lang"})})
 */
class ShopPayment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $payid;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $link;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $sort;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false, options={"default":"'0'"})
     */
    private $hide;

    /**
     * @ORM\Column(type="enum", nullable=false, options={"default":"'normal'"})
     */
    private $type;
}