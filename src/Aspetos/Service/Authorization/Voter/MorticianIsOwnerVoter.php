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
use FOS\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class MorticianIsOwnerVoter
 *
 * @package Aspetos\Service\Authorization\Voter
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.authorization.voter.mortician_is_owner")
 * @DI\Tag("security.voter")
 */
class MorticianIsOwnerVoter extends AbstractVoter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    /**
     * @return array
     */
    protected function getSupportedAttributes()
    {
        return array(
            self::VIEW,
            self::EDIT
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
     * @param string        $attribute
     * @param Mortician     $mortician
     * @param MorticianUser $user
     *
     * @return bool
     */
    protected function isGranted($attribute, $mortician, $user = null)
    {
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (!$user instanceof MorticianUser) {
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
            case self::EDIT:
                return ($mortician == $user->getMortician());
                break;
        }

        return false;
    }
}
