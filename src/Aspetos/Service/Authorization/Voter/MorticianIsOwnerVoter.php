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

use Aspetos\Model\Entity\Mortician;
use Aspetos\Model\Entity\MorticianUser;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class MorticianIsOwnerVoter
 *
 * @package Aspetos\Service\Authorization\Voter
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.authorization.voter.mortician_is_owner", parent="aspetos.service.authorization.voter.abstract_voter")
 * @DI\Tag("security.voter")
 */
class MorticianIsOwnerVoter extends AbstractVoter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * @return array
     */
    protected function getSupportedAttributes()
    {
        return array(
            self::VIEW,
            self::EDIT,
            self::DELETE
        );
    }

    /**
     * @return array
     */
    protected function getSupportedClasses()
    {
        return array(
            'Aspetos\Model\Entity\Mortician'
        );
    }

    /**
     * @param string         $attribute
     * @param Mortician      $mortician
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function isGranted($attribute, $mortician, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        // Admin is always allowed
        if ($this->isAdmin($token)) {
            return true;
        }

        // Only if user is a MorticianUser
        if (!$user instanceof MorticianUser) {
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return ($mortician == $user->getMortician());
                break;
            //case self::DELETE:
            case self::EDIT:
                return ($mortician == $user->getMortician() && $this->hasRole($token, 'ROLE_MORTICIAN_ADMIN'));
                break;
        }

        return false;
    }
}
