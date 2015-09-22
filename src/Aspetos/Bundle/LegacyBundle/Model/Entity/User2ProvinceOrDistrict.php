<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_user2es_provinceOrDistrict",
 *     indexes={
 *         @ORM\Index(name="provinceOrDistrictGroup", columns={"group"}),
 *         @ORM\Index(name="es_user2es_provinceOrDistrictUid", columns={"uid"}),
 *         @ORM\Index(name="es_user2es_Country_provinceOrDistrict", columns={"countryId"}),
 *         @ORM\Index(name="es_user2es_Province_provinceOrDistrict", columns={"provinceId"}),
 *         @ORM\Index(name="es_user2es_District_provinceOrDistrict", columns={"districtId"})
 *     }
 * )
 */
class User2ProvinceOrDistrict
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $group;
}