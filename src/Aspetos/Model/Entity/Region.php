<?php
namespace Aspetos\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Cemetery", mappedBy="region", cascade={"persist"})
     */
    private $cemetery;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Country", inversedBy="region")
     * @ORM\JoinColumn(name="countryId", referencedColumnName="id", nullable=false)
     */
    private $country;
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
     * Add cemetery
     *
     * @param \Aspetos\Model\Entity\Cemetery $cemetery
     * @return Region
     */
    public function addCemetery(\Aspetos\Model\Entity\Cemetery $cemetery)
    {
        $this->cemetery[] = $cemetery;

        return $this;
    }

    /**
     * Remove cemetery
     *
     * @param \Aspetos\Model\Entity\Cemetery $cemetery
     */
    public function removeCemetery(\Aspetos\Model\Entity\Cemetery $cemetery)
    {
        $this->cemetery->removeElement($cemetery);
    }

    /**
     * Get cemetery
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCemetery()
    {
        return $this->cemetery;
    }

    /**
     * Set country
     *
     * @param \Aspetos\Model\Entity\Country $country
     * @return Region
     */
    public function setCountry(\Aspetos\Model\Entity\Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Aspetos\Model\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }
}
