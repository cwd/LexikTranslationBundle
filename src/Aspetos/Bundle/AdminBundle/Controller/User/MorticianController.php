<?php
/*
 * This file is part of AspetosAdminBundle
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Aspetos\Bundle\AdminBundle\Controller\User;

use Aspetos\Model\Entity\MorticianUser;
use JMS\SecurityExtraBundle\Annotation\SatisfiesParentSecurityPolicy;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User/MorticianController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @Route("/user/mortician")
 */
class MorticianController extends UserController
{
    /**
     * Set raw option values right before validation. This can be used to chain
     * options in inheritance setups.
     *
     * @return array
     */
    protected function setOptions()
    {
        $options = array(
            'checkModelClass'   => 'Aspetos\Model\Entity\MorticianUser',
            'entityFormType'    => 'aspetos_admin_form_user_mortician',
            'title'             => 'Mortician',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * @Route("/create")
     * @Method({"GET", "POST"})
     * @SatisfiesParentSecurityPolicy()
     * @Secure("ROLE_ADMIN")
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $crudObject = new MorticianUser();

        return $this->formHandler($crudObject, $request, true);
    }

    /**
     * Edit action
     *
     * @ParamConverter("crudObject", class="Model:BaseUser")
     * @SatisfiesParentSecurityPolicy()
     * @Secure("ROLE_ADMIN")
     * @Route("/edit/{id}")
     *
     * @param UserInterface $crudObject
     * @param Request       $request
     *
     * @return RedirectResponse|Response
     */
    public function editAction(UserInterface $crudObject, Request $request)
    {
        return $this->formHandler($crudObject, $request, false);
    }
}
