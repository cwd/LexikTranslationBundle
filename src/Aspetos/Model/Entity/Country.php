<?php
namespace Aspetos\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CountryRepository")
 */
class Country
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
     * @ORM\Column(type="string", length=2, nullable=false)
     */
    private $alpha2;

    /**
     * @ORM\Column(type="string", length=3, nullable=false)
     */
    private $alpha3;

    /**
     * @ORM\Column(type="string", unique=true, length=3, nullable=false)
     */
    private $countryCode;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Region", mappedBy="country")
     */
    private $region;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->region = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Country
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
     * Add region
     *
     * @param \Aspetos\Model\Entity\Region $region
     * @return Country
     */
    public function addRegion(\Aspetos\Model\Entity\Region $region)
    {
        $this->region[] = $region;

        return $this;
    }

    /**
     * Remove region
     *
     * @param \Aspetos\Model\Entity\Region $region
     */
    public function removeRegion(\Aspetos\Model\Entity\Region $region)
    {
        $this->region->removeElement($region);
    }

    /**
     * Get region
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegion()
    {
        return $this->region;
    }
}
