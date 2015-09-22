<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\RoleRepository")
 */
class Role
{
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
     * 
     */
    private $Users;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->Users = new \Doctrine\Common\Collections\ArrayCollection();
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
        $this->Users[] = $users;

        return $this;
    }

    /**
     * Remove Users
     *
     * @param \Aspetos\Model\Entity\User $users
     */
    public function removeUser(\Aspetos\Model\Entity\User $users)
    {
        $this->Users->removeElement($users);
    }

    /**
     * Get Users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->Users;
    }
}
