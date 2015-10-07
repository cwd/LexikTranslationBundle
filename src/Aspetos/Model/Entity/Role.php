<?php
namespace Aspetos\Model\Entity;
use Aspetos\Model\Traits\Blameable;
use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\RoleRepository")
 */
class Role
{
    use Timestampable;
    use Blameable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\User", mappedBy="userRoles")
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Role
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
     * Add Users
     *
     * @param \Aspetos\Model\Entity\User $users
     * @return Role
     */
    public function addUser(\Aspetos\Model\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove Users
     *
     * @param \Aspetos\Model\Entity\User $users
     */
    public function removeUser(\Aspetos\Model\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get Users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
