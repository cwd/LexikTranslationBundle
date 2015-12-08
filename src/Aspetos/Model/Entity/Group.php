<?php

namespace Aspetos\Model\Entity;

use Cwd\GenericBundle\Doctrine\Traits\Timestampable;
use Doctrine\ORM\Mapping AS ORM;
use FOS\UserBundle\Model\Group as FosGroup;

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
     * @param string $name
     * @param array  $roles
     */
    public function __construct($name, $roles = array())
    {
        parent::__construct($name, $roles);
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
}
