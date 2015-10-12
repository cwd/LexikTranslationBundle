<?php

namespace Aspetos\Model\Entity;

use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use FOS\UserBundle\Entity\Group as FosGroup;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\GroupRepository")
 * @ORM\Table(name="FosGroup")
 */
class Group extends FosGroup
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="array")
     */
    protected $roles;

    public function __construct($name, $roles = array())
    {
        parent::__construct($name, $roles);
    }
}
