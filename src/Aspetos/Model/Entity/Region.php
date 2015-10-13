<?php
namespace Aspetos\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\RegionRepository")
 */
class Region
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(groups={"default"}, max = 200)
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @Assert\Length(
     *      groups={"default"},
     *      min = 4
     * )
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(
     *      groups={"default"},
     *      max = 2
     * )
     * @ORM\Column(type="string", length=2, nullable=false)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $short;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\District", mappedBy="region", cascade={"persist"})
     */
    private $districts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cemetery = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Region
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Region
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Region
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * @param mixed $short
     *
     * @return $this
     */
    public function setShort($short)
    {
        $this->short = $short;

        return $this;
    }

    /**
     * Add districts
     *
     * @param \Aspetos\Model\Entity\District $districts
     * @return Region
     */
    public function addDistrict(\Aspetos\Model\Entity\District $districts)
    {
        $this->districts[] = $districts;

        return $this;
    }

    /**
     * Remove districts
     *
     * @param \Aspetos\Model\Entity\District $districts
     */
    public function removeDistrict(\Aspetos\Model\Entity\District $districts)
    {
        $this->districts->removeElement($districts);
    }

    /**
     * Get districts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDistricts()
    {
        return $this->districts;
    }
}
