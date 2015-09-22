<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\UserRepository")
 * @ORM\InheritanceType("JOINED")
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
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
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
     * @ORM\ManyToMany(targetEntity="Aspetos\Model\Entity\Role", inversedBy="Users")
     * @ORM\JoinTable(
     *     name="UserHasRole",
     *     joinColumns={@ORM\JoinColumn(name="userId", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="roleId", referencedColumnName="id", nullable=false)}
     * )
     */
    private $UserRoles;
}