<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Authorization\Voter;

use Aspetos\Model\Entity\Customer;
use Aspetos\Model\Entity\Mortician;
use Aspetos\Model\Entity\MorticianUser;
use Aspetos\Model\Entity\SupplierUser;
use Aspetos\Service\PermissionService;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Aspetos\Service\UserInterface as AspetosUserInterface;

/**
 * Class IsOwnerWithPermissionOrAdminVoter
 *
 * @package Aspetos\Service\Authorization\Voter
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class IsOwnerWithPermissionOrAdminVoter extends AbstractVoter
{
    /**
     * @var PermissionService
     */
    protected $permissionService;

    /**
     * @param RoleHierarchyInterface $roleHierarchy
     * @param PermissionService      $permissionService
     */
    public function __construct(RoleHierarchyInterface $roleHierarchy, PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;

        parent::__construct($roleHierarchy);
    }

    /**
     * @return array
     */
    protected function getSupportedAttributes()
    {
        return $this->permissionService->findAllAsArray();
    }

    /**
     * @return array
     */
    protected function getSupportedClasses()
    {
        return array_keys(self::getSuportedClassMap());
    }

    /**
     * @param string         $attribute
     * @param misc           $object
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function isGranted($attribute, $object, TokenInterface $token)
    {
        $user = $token->getUser();


        if (!($user instanceof UserInterface || $user instanceof AspetosUserInterface)) {
            return false;
        }

        // Admin is always allowed
        if ($this->isAdmin($token)) {
            return true;
        }

        if ($object instanceOf Mortician && $user->getMorticianUser() !== null) {
            $baseObject = $user->getMorticianUser()->getMortician();
        } elseif ($object instanceOf Supplier && $user->getSupplierUser() !== null) {
            $baseObject = $user->getSupplierUser()->getSupplier();
        } elseif ($object instanceOf Customer && $user->getCustomer() !== null) {
            $baseObject = $user->getCustomer();
        } else {
            return false;
        }

        if ($baseObject != $object) {
            return false;
        }

        return $this->userHasPermission($user, $attribute);
    }

    /**
     * @param UserInterface $user
     * @param string        $permission
     *
     * @return bool
     * @todo centralice in user model
     */
    protected function userHasPermission(UserInterface $user, $permission)
    {
        foreach ($user->getPermissions() as $perm) {
            if ($perm->getName() == $permission) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    static public function getSuportedClassMap()
    {
        return array(
            'Aspetos\Model\Entity\Mortician' => 'Mortician',
            'Aspetos\Model\Entity\Supplier'  => 'Supplier',
            'Aspetos\Model\Entity\Costumer'  => 'Costumer'
        );
    }
}
