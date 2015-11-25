<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\CustomerRepository")
 */
class Customer extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $activatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $disclaimerAcceptedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $newsletterSignupAt;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    private $newsletter;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $forumId;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\BaseUser", inversedBy="customer")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id", unique=true)
     */
    private $baseUser;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\Obituary", mappedBy="customer", cascade={"persist"})
     */
    private $obituary;
    /**
     * @ORM\OneToMany(
     *     targetEntity="Aspetos\Model\Entity\CustomerAddress",
     *     mappedBy="customer",
     *     cascade={"persist","remove"}
     * )
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="Aspetos\Model\Entity\CustomerOrder", mappedBy="customer")
     */
    private $orders;

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_CUSTOMER;
    }

    /**
     * Add addresses
     *
     * @param \Aspetos\Model\Entity\CustomerAddress $addresses
     * @return Customer
     */
    public function addAddress(\Aspetos\Model\Entity\CustomerAddress $addresses)
    {
        $this->addresses[] = $addresses;

        return $this;
    }

    /**
     * Remove addresses
     *
     * @param \Aspetos\Model\Entity\CustomerAddress $addresses
     */
    public function removeAddress(\Aspetos\Model\Entity\CustomerAddress $addresses)
    {
        $this->addresses->removeElement($addresses);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Add orders
     *
     * @param \Aspetos\Model\Entity\CustomerOrder $orders
     * @return Customer
     */
    public function addOrder(\Aspetos\Model\Entity\CustomerOrder $orders)
    {
        $this->orders[] = $orders;

        return $this;
    }

    /**
     * Remove orders
     *
     * @param \Aspetos\Model\Entity\CustomerOrder $orders
     */
    public function removeOrder(\Aspetos\Model\Entity\CustomerOrder $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Add obituary
     *
     * @param \Aspetos\Model\Entity\Obituary $obituary
     * @return Customer
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

    /**
     * @return Datetime
     */
    public function getActivatedAt()
    {
        return $this->activatedAt;
    }

    /**
     * @param Datetime $activatedAt
     *
     * @return $this
     */
    public function setActivatedAt($activatedAt)
    {
        $this->activatedAt = $activatedAt;

        return $this;
    }

    /**
     * @return Datetime
     */
    public function getDisclaimerAcceptedAt()
    {
        return $this->disclaimerAcceptedAt;
    }

    /**
     * @param Datetime $disclaimerAcceptedAt
     *
     * @return $this
     */
    public function setDisclaimerAcceptedAt($disclaimerAcceptedAt)
    {
        $this->disclaimerAcceptedAt = $disclaimerAcceptedAt;

        return $this;
    }

    /**
     * @return Datetime
     */
    public function getNewsletterSignupAt()
    {
        return $this->newsletterSignupAt;
    }

    /**
     * @param Datetime $newsletterSignupAt
     *
     * @return $this
     */
    public function setNewsletterSignupAt($newsletterSignupAt)
    {
        $this->newsletterSignupAt = $newsletterSignupAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * @param bool $newsletter
     *
     * @return $this
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getForumId()
    {
        return $this->forumId;
    }

    /**
     * @param mixed $forumId
     *
     * @return $this
     */
    public function setForumId($forumId)
    {
        $this->forumId = $forumId;

        return $this;
    }
}
