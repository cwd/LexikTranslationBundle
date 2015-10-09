<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_shop_product",
 *     indexes={
 *         @ORM\Index(name="name_lang", columns={"name_lang"}),
 *         @ORM\Index(name="description_lang", columns={"description_lang"}),
 *         @ORM\Index(name="product2uid", columns={"uid"})
 *     }
 * )
 *
 */
class ShopProduct
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $pid;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $description_lang;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false)
     */
    private $hide;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $category;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $sort;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=true, scale=2, options={"default":"NULL"})
     */
    private $priceGross;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"'20'"})
     */
    private $vat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $image1;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $imageDescription1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $image2;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $imageDescription2;

    /**
     * @ORM\Column(type="string", length=45, nullable=true, options={"default":"NULL"})
     */
    private $image3;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $imageDescription3;

    /**
     * @ORM\Column(type="timestamp", nullable=true, options={"default":"CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"})
     */
    private $timestamp;
}
