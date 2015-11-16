<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\DistrictRepository")
 */
class District
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\RelativeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="relationField", value="region"),
     *          @Gedmo\SlugHandlerOption(name="relationSlugField", value="slug"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="/")
     *      })
     * }, fields={"name"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Obituary", mappedBy="district", cascade={"persist"})
     */
    private $obituary;

    /**
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\Region", inversedBy="districts")
     * @ORM\JoinColumn(name="regionId", referencedColumnName="id", nullable=false)
     */
    private $region;

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
     * @return District
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
     * @return District
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
     * Set region
     *
     * @param \Aspetos\Model\Entity\Region $region
     * @return District
     */
    public function setRegion(\Aspetos\Model\Entity\Region $region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \Aspetos\Model\Entity\Region
     */
    public function getRegion()
    {
        return $this->region;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->obituary = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add obituary
     *
     * @param \Aspetos\Model\Entity\Obituary $obituary
     * @return District
     */
    public function addObituary(\Aspetos\Model\Entity\Obituary $obituary)
    {
        $this->obituary[] = $obituary;

        return $this;
    }

    /**
     * Remove obituary
     *
     * @param \Aspetos\Model\Entity\Obituary $obituary
     */
    public function removeObituary(\Aspetos\Model\Entity\Obituary $obituary)
    {
        $this->obituary->removeElement($obituary);
    }

    /**
     * Get obituary
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getObituary()
    {
        return $this->obituary;
    }
}
