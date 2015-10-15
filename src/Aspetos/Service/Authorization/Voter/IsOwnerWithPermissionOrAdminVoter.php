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
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
        //@todo dont like this.
        return array(
            'Aspetos\Model\Entity\Mortician',
            'Aspetos\Model\Entity\Supplier',
            'Aspetos\Model\Entity\Costumer'
        );
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

        if (!$user instanceof UserInterface) {
            return false;
        }

        // Admin is always allowed
        if ($this->isAdmin($token)) {
            return true;
        }

        if ($user instanceof MorticianUser) {
            $baseObject = $user->getMortician();
        } elseif ($user instanceof SupplierUser) {
            $baseObject = $user->getSupplier();
        } elseif ($user instanceof Customer) {
            $baseObject = $user;
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
}
