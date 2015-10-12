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
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
    /**
     * Set raw option values right before validation. This can be used to chain
     * options in inheritance setups.
     *
     * @return array
     */
    protected function setOptions()
    {
        $options = array(
            'entityService'     => 'aspetos.service.user',
            'entityFormType'    => 'aspetos_admin_form_user_admin',
            'gridService'       => 'aspetos.admin.grid.user',
            'icon'              => 'fa fa-users',
            'redirectRoute'     => 'aspetos_admin_user_user_list',
            'title'             => 'User',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * @Route("/detail/{id}")
     * @Template()
     * @ParamConverter("crudObject", class="Model:User")
     *
     * @param UserInterface $crudObject
     *
     * @return array
     */
    public function detailAction(UserInterface $crudObject)
    {
        return array("crudObject" => $crudObject);
    }

    /**
     * @Route("/create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $object = new Admin();

        return $this->formHandler($object, $request, true);
    }

    /**
     * Edit action
     *
     * @ParamConverter("crudObject", class="Model:User")
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

    /**
     * @Route("/delete/{id}")
     * @ParamConverter("crudObject", class="Model:User")
     * @Method({"GET", "DELETE"})
     *
     * @param UserInterface $crudObject
     * @param Request       $request
     *
     * @return RedirectResponse
     */
    public function deleteAction(UserInterface $crudObject, Request $request)
    {
        return $this->deleteHandler($crudObject, $request);
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
