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

use Aspetos\Bundle\AdminBundle\Controller\BaseController;
use Aspetos\Model\Entity\Admin;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 * @Route("/user")
 */
class UserController extends BaseController
{
    protected $options = array(
        'entityService' => 'aspetos.service.handler.user',
        'entityFormType' => 'aspetos_admin_form_user_admin',
        'gridService' => 'aspetos.admin.grid.user',
        'icon' => 'fa fa-users',
        'redirectRoute' => 'aspetos_admin_user_user_list',
    );

    /**
     * @param UserInterface $user
     *
     * @Route("/detail/{id}")
     * @Template()
     * @ParamConverter("user", class="Model:User")
     *
     * @return array
     */
    public function detailAction(UserInterface $user)
    {
        return array("user" => $user);
    }

    /**
     * @param Request $request
     *
     * @Route("/create")
     * @Method({"GET", "POST"})
     * @return array
     */
    public function createAction(Request $request)
    {
        $object = new Admin();

        return $this->formHandler($object, $request, true);
    }

    /**
     * Edit action
     * @param UserInterface $user
     * @param Request       $request
     *
     * @ParamConverter("user", class="Model:User")
     * @Route("/edit/{id}")
     * @Template()
     * @return array
     */
    public function editAction(UserInterface $user, Request $request)
    {
        return $this->formHandler($user, $request);
    }

    /**
     * @param UserInterface $user
     * @param Request       $request
     *
     * @Route("/delete/{id}")
     * @ParamConverter("user", class="Model:User")
     * @Method({"GET", "DELETE"})
     * @return RedirectResponse
     */
    public function deleteAction(UserInterface $user, Request $request)
    {
        return $this->deleteHandler($user, $request);
    }

    /**
     * @Route("/list")
     * @Route("/")
     * @Template()
     *
     * @return array
     */
    public function listAction()
    {
        $this->getGrid();

        return array();
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Grid action
     *
     * @Route("/grid")
     * @return Response
     */
    public function gridAction()
    {
        return $this->getGrid()->execute();
    }
}
