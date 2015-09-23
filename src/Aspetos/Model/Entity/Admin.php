<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\AdminRepository")
 */
class Admin extends \Aspetos\Model\Entity\User
{
    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_ADMIN;
    }
}
