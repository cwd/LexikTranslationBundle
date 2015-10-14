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

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Class BaseVoter
 *
 * @package Aspetos\Service\Authorization\Voter
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.authorization.voter.abstract_voter", public=false)
 * DI\Tag("security.voter")
 */
abstract class AbstractVoter extends RoleHierarchyVoter
{
    /**
     * @param RoleHierarchyInterface $roleHierarchy
     * @DI\InjectParams({
     *  "roleHierarchy" = @DI\Inject("aspetos_role_hierarchy")
     * })
     */
    public function __construct(RoleHierarchyInterface $roleHierarchy)
    {
        parent::__construct($roleHierarchy);
    }

    /**
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function isAdmin(TokenInterface $token)
    {
        return $this->hasRole($token, 'ROLE_ADMIN');
    }

    /**
     * @param TokenInterface $token
     * @param string         $targetRole
     *
     * @return bool
     */
    protected function hasRole(TokenInterface $token, $targetRole)
    {
        $reachableRoles = $this->extractRoles($token);
        foreach ($reachableRoles as $role) {
            if ($role->getRole() == $targetRole) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, $this->getSupportedAttributes());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        foreach ($this->getSupportedClasses() as $supportedClass) {
            if ($supportedClass === $class || is_subclass_of($class, $supportedClass)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Iteratively check all given attributes by calling isGranted.
     *
     * This method terminates as soon as it is able to return ACCESS_GRANTED
     * If at least one attribute is supported, but access not granted, then ACCESS_DENIED is returned
     * Otherwise it will return ACCESS_ABSTAIN
     *
     * @param TokenInterface $token      A TokenInterface instance
     * @param object         $object     The object to secure
     * @param array          $attributes An array of attributes associated with the method being invoked
     *
     * @return int either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (!$object || !$this->supportsClass(get_class($object))) {
            return self::ACCESS_ABSTAIN;
        }

        // abstain vote by default in case none of the attributes are supported
        $vote = self::ACCESS_ABSTAIN;

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            // as soon as at least one attribute is supported, default is to deny access
            $vote = self::ACCESS_DENIED;

            if ($this->isGranted($attribute, $object, $token)) {
                // grant access as soon as at least one voter returns a positive response
                return self::ACCESS_GRANTED;
            }
        }

        return $vote;
    }
}
