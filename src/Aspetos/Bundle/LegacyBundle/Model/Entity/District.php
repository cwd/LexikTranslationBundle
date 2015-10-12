<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(
 *     name="es_district",
 *     indexes={@ORM\Index(name="provinceId", columns={"provinceId"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="prettyUrl", columns={"prettyUrl"})}
 * )
 *
 */
class District
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $districtId;

    /**
     * @ORM\Column(type="string", length=50, nullable=false, options={"default":""})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $prettyUrl;

    /**
     * @ORM\Column(type="string", length=10, nullable=true, options={"default":"NULL"})
     */
    private $de_id1;

    /**
     * @ORM\Column(type="string", length=10, nullable=true, options={"default":"NULL"})
     */
    private $de_id2;

    /**
     * @return mixed
     */
    public function getDistrictId()
    {
        return $this->districtId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPrettyUrl()
    {
        return $this->prettyUrl;
    }

    /**
     * @return mixed
     */
    public function getDeId1()
    {
        return $this->de_id1;
    }

    /**
     * @return mixed
     */
    public function getDeId2()
    {
        return $this->de_id2;
    }
}
