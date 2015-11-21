<?php
/*
 * This file is part of AspetosAdminBundle
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Aspetos\Bundle\AdminBundle\Controller;

use Aspetos\Model\Entity\Permission;
use Aspetos\Model\Entity\PermissionAdministration;
use Aspetos\Service\Exception\PermissionNotFoundException;
use Aspetos\Service\PermissionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Cwd\GenericBundle\Controller\GenericController as CwdController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Url;

/**
 * Class PermissionController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_SUPER_ADMIN')")
 * @Route("/permission")
 */
class PermissionController extends BaseController
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
            'entityService'     => 'aspetos.service.permission',
            'entityFormType'    => 'aspetos_admin_form_permission',
            'gridService'       => 'aspetos.admin.grid.permission',
            'icon'              => 'fa fa-lock',
            'redirectRoute'     => 'aspetos_admin_permission_list',
            'title'             => 'Permission',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * @Route("/detail/{id}")
     * @Template()
     * @ParamConverter("crudObject", class="Model:Permission")
     *
     * @param Permission $crudObject
     *
     * @return array
     */
    public function detailAction(Permission $crudObject)
    {
        return array("crudObject" => $crudObject);
    }

    /**
     * Edit action
     *
     * @ParamConverter("crudObject", class="Model:Permission")
     * @Route("/edit/{id}")
     *
     * @param Permission $crudObject
     * @param Request    $request
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Permission $crudObject, Request $request)
    {
        return $this->formHandler($crudObject, $request, false);
    }

    /**
     * @Route("/delete/{id}")
     * @ParamConverter("crudObject", class="Model:Permission")
     * @Method({"GET", "DELETE"})
     *
     * @param Permission $crudObject
     * @param Request    $request
     *
     * @return RedirectResponse
     */
    public function deleteAction(Permission $crudObject, Request $request)
    {
        return $this->deleteHandler($crudObject, $request);
    }
}
