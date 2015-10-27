<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true, repositoryClass="Aspetos\Bundle\LegacyBundle\Model\Repository\AttributeRepository")
 * @ORM\Table(
 *     name="es_attribute",
 *     indexes={@ORM\Index(name="groupid", columns={"groupid"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="uniqueGroupIdPrettyUrl", columns={"groupid","prettyUrl"})}
 * )
 *
 */
class Attribute
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $attid;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"NULL"})
     */
    private $prettyUrl;

    /**
     * @ORM\Column(type="enum", nullable=false, options={"default":"'radio'"})
     */
    private $type;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $unit;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $doNotDelete;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $sort;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $company;

    /**
     * @return mixed
     */
    public function getAttid()
    {
        return $this->attid;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @return mixed
     */
    public function getDoNotDelete()
    {
        return $this->doNotDelete;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }
}
