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

use Aspetos\Model\Entity\Admin;
use JMS\SecurityExtraBundle\Annotation\SatisfiesParentSecurityPolicy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AdminUserController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @Route("/user/admin")
 */
class AdminController extends UserController
{
    /**
     * @param Request $request
     *
     * @Route("/create")
     * @Method({"GET", "POST"})
     * @SatisfiesParentSecurityPolicy()
     * @Secure("ROLE_ADMIN")
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $object = new Admin();

        return $this->formHandler($object, $request, true, 'aspetos_admin_form_user_admin', 'Admin');
    }

    /**
     * Edit action
     * @param UserInterface $user
     * @param Request       $request
     *
     * @ParamConverter("user", class="Model:User")
     * @SatisfiesParentSecurityPolicy()
     * @Secure("ROLE_ADMIN")
     * @Route("/edit/{id}")
     * @Template()
     * @return array
     */
    public function editAction(UserInterface $user, Request $request)
    {
        return $this->formHandler($user, $request, false, 'aspetos_admin_form_user_admin', 'Admin');
    }
}
