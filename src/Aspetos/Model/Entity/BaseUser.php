<?php

namespace Aspetos\Model\Entity;

use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use FOS\UserBundle\Entity\User as FOSUser;
use FOS\UserBundle\Model\User;
use Gedmo\Mapping\Annotation as Gedmo;
use KPhoen\DoctrineStateMachineBehavior\Entity\Stateful;
use KPhoen\DoctrineStateMachineBehavior\Entity\StatefulTrait;
use Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints as RollerworksPassword;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\UserRepository")
 * @ORM\Table(name="User")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 *
 *
 * @UniqueEntity(fields={"email"}, groups={"create"})
 */
class BaseUser extends FOSUser implements AdvancedUserInterface, Stateful
{
    use Timestampable;
    use Blameable;
    use StatefulTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(max = "150", groups={"default"})
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(max = "150", groups={"default"})
     */
    protected $lastname;

    /**
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Email(groups={"default"})
     * @Assert\Length(max = "200", groups={"default"})
     */
    protected $email;

    /**
     * @Assert\NotBlank(groups={"create"})
     * @RollerworksPassword\PasswordStrength(minLength=6, minStrength=3, message="Password to weak", groups={"create", "default"})
     */
    protected $plainPassword;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $state;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\Customer", mappedBy="baseUser", cascade={"persist"})
     */
    private $customer;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\Admin", mappedBy="user", cascade={"persist"})
     */
    private $admin;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\MorticianUser", mappedBy="user", cascade={"persist"})
     */
    private $morticianUser;

    /**
     * @ORM\OneToOne(targetEntity="Aspetos\Model\Entity\SupplierUser", mappedBy="user", cascade={"persist"})
     */
    private $supplierUser;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Group", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="UserGroup",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Permission", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="UserHasPermission",
     *     joinColumns={@ORM\JoinColumn(name="userId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="permissionId", referencedColumnName="id", nullable=false)}
     * )
     */
    private $permissions;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setEnabled(true);
    }

    /**
     * Sets the object state.
     * Used by the StateMachine behavior
     *
     * @return string
     */
    public function getFiniteState()
    {
        return $this->getState();
    }

    /**
     * Sets the object state.
     * Used by the StateMachine behavior
     *
     * @param string $state
     * @return Company
     */
    public function setFiniteState($state)
    {
        return $this->setState($state);
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return User
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param string $email
     *
     * @return $this|\FOS\UserBundle\Model\UserInterface
     */
    public function setEmail($email)
    {
        $this->setUsername($email);

        return parent::setEmail($email);
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * BC for Cwd\GenericBundle\Handler\AuthenticationHandler
     *
     * @param \DateTime $datetime
     * @deprecated
     */
    public function setLastLoginAt(\DateTime $datetime)
    {

    }

    /**
     * Used by test fixtures loader
     *
     * @param int $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Add permissions
     *
     * @param \Aspetos\Model\Entity\Permission $permission
     * @return BaseUser
     */
    public function addPermission(\Aspetos\Model\Entity\Permission $permission)
    {
        $this->permissions[] = $permission;

        return $this;
    }

    /**
     * Remove permissions
     *
     * @param \Aspetos\Model\Entity\Permission $permissions
     */
    public function removePermission(\Aspetos\Model\Entity\Permission $permissions)
    {
        $this->permissions->removeElement($permissions);
    }

    /**
     * Get permissions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        $types = array();

        if ($this->getAdmin() !== null) {
            $types[] = 'admin';
        }

        if ($this->getMortician() !== null) {
            $types[] = 'mortician';
        }

        if ($this->getSupplier() !== null) {
            $types[] = 'supplier';
        } elseif ($this->getCustomer() != null) {
            $types[] = 'customer';
        }

        return $types;
    }

    /**
     * Set customer
     *
     * @param \Aspetos\Model\Entity\Customer $customer
     * @return BaseUser
     */
    public function setCustomer(\Aspetos\Model\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Aspetos\Model\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }


    /**
     * Set admin
     *
     * @param \Aspetos\Model\Entity\Admin $admin
     * @return BaseUser
     */
    public function setAdmin(\Aspetos\Model\Entity\Admin $admin = null)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return \Aspetos\Model\Entity\Admin
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set morticianUser
     *
     * @param \Aspetos\Model\Entity\MorticianUser $morticianUser
     * @return BaseUser
     */
    public function setMorticianUser(\Aspetos\Model\Entity\MorticianUser $morticianUser = null)
    {
        $this->morticianUser = $morticianUser;

        return $this;
    }

    /**
     * Get morticianUser
     *
     * @return \Aspetos\Model\Entity\MorticianUser
     */
    public function getMorticianUser()
    {
        return $this->morticianUser;
    }

    /**
     *
     * @return Mortician|null
     */
    public function getMortician()
    {
        if ($this->getMorticianUser() !== null) {
            return $this->getMorticianUser()->getMortician();
        }

        return null;
    }

    /**
     * Set supplierUser
     *
     * @param \Aspetos\Model\Entity\SupplierUser $supplierUser
     * @return BaseUser
     */
    public function setSupplierUser(\Aspetos\Model\Entity\SupplierUser $supplierUser = null)
    {
        $this->supplierUser = $supplierUser;

        return $this;
    }

    /**
     * Get supplierUser
     *
     * @return \Aspetos\Model\Entity\SupplierUser
     */
    public function getSupplierUser()
    {
        return $this->supplierUser;
    }

    /**
     * @return Supplier|null
     */
    public function getSupplier()
    {
        if ($this->getSupplierUser() !== null) {
            return $this->getSupplierUser()->getSupplier();
        }

        return null;
    }
}
