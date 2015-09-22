<?php
namespace Aspetos\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Aspetos\Model\Repository\AdminRepository")
 */
class Admin extends \Aspetos\Model\Entity\User
{
}
