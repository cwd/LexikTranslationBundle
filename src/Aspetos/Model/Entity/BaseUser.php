<?php

namespace Aspetos\Model\Entity;

use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use FOS\UserBundle\Entity\User as FOSUser;
use FOS\UserBundle\Model\User;
use Gedmo\Mapping\Annotation as Gedmo;
use Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints as RollerworksPassword;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\UserRepository")
 * @ORM\Table(name="User")
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
class BaseUser extends FOSUser implements AdvancedUserInterface
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
    protected $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(max = "150", groups={"default"})
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     * @Assert\NotBlank(groups={"default"})
     * @Assert\Length(max = "150", groups={"default"})
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setEnabled(true);
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

    public function setEmail($email)
    {
        return $this->setUsername($email);
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
}
