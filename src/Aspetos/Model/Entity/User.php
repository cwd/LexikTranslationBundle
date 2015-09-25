<?php
namespace Aspetos\Model\Entity;
use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Cwd\GenericBundle\LegacyHelper\Utils;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\Encoder\Pbkdf2PasswordEncoder;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\UserRepository")
 * @ORM\InheritanceType("JOINED")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true)
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *     "customer"="Aspetos\Model\Entity\Customer",
 *     "admin"="Aspetos\Model\Entity\Admin",
 *     "mortician"="Aspetos\Model\Entity\MorticianUser",
 *     "supplier"="Aspetos\Model\Entity\SupplierUser"
 * }
 * )
 */
class User implements AdvancedUserInterface
{
    use Timestampable;
    use Blameable;

    // Make the discriminator accessible
    const TYPE_CUSTOMER  = 'customer';
    const TYPE_ADMIN     = 'admin';
    const TYPE_MORTICIAN = 'mortician';
    const TYPE_SUPPLIER  = 'supplier';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(max = "150", groups={"default"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(max = "150", groups={"default"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Email(groups={"default"})
     * @Assert\Length(max = "200", groups={"default"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     * @Assert\NotBlank(groups={"create"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $salt;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":1})
     */
    private $enabled;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    private $locked;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $passwordToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $passwordUpdatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLoginAt;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(
     *     name="UserHasRole",
     *     joinColumns={@ORM\JoinColumn(name="userId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="roleId", referencedColumnName="id", nullable=false)}
     * )
     * @Assert\Count(min="1", groups={"default"}, minMessage="You must specifiy at least one role")
     */
    private $userRoles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userRoles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setEnabled(true);
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
     * @param mixed $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        if ($password != null) {
            $encoder = new Pbkdf2PasswordEncoder('sha512', true, 1000, 40);
            $salt = Utils::generateRandomString(20);
            $this->salt = $salt;
            $this->password = $encoder->encodePassword($password, $salt);
        }

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return User
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     * @deprecated
     */
    public function getEnabled()
    {
        return $this->isEnabled();
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return bool
     * @deprecated
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return $this->locked;
    }

    /**
     * @param mixed $locked
     *
     * @return $this
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
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
     * Set passwordToken
     *
     * @param string $passwordToken
     * @return User
     */
    public function setPasswordToken($passwordToken)
    {
        $this->passwordToken = $passwordToken;

        return $this;
    }

    /**
     * Get passwordToken
     *
     * @return string
     */
    public function getPasswordToken()
    {
        return $this->passwordToken;
    }

    /**
     * Set passwordUpdatedAt
     *
     * @param \DateTime $passwordUpdatedAt
     * @return User
     */
    public function setPasswordUpdatedAt($passwordUpdatedAt)
    {
        $this->passwordUpdatedAt = $passwordUpdatedAt;

        return $this;
    }

    /**
     * Get passwordUpdatedAt
     *
     * @return \DateTime
     */
    public function getPasswordUpdatedAt()
    {
        return $this->passwordUpdatedAt;
    }

    /**
     * Set lastLoginAt
     *
     * @param \DateTime $lastLoginAt
     * @return User
     */
    public function setLastLoginAt($lastLoginAt)
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }

    /**
     * Get lastLoginAt
     *
     * @return \DateTime
     */
    public function getLastLoginAt()
    {
        return $this->lastLoginAt;
    }

    /**
     * Add UserRoles
     *
     * @param \Aspetos\Model\Entity\Role $userRoles
     * @return User
     */
    public function addUserRole(\Aspetos\Model\Entity\Role $userRoles)
    {
        $this->userRoles[] = $userRoles;

        return $this;
    }

    /**
     * Remove UserRoles
     *
     * @param \Aspetos\Model\Entity\Role $userRoles
     */
    public function removeUserRole(\Aspetos\Model\Entity\Role $userRoles)
    {
        $this->userRoles->removeElement($userRoles);
    }

    /**
     * Get UserRoles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return !$this->isLocked();
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        $r = array();
        $roles = $this->getUserRoles();
        if (count($roles) > 0) {
            foreach ($roles as $role) {
                $r[] = $role->getRole();
            }
        }

        return $r;
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
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @return null
     */
    public function eraseCredentials()
    {
        return null;
    }
}
