<?php
/*
 * This file is part of AspetosAdminBundle
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Aspetos\Bundle\AdminBundle\Controller\Mortician;

use Aspetos\Bundle\AdminBundle\Controller\BaseController;
use Aspetos\Model\Entity\Mortician;
use Aspetos\Model\Entity\MorticianUser;
use Aspetos\Service\Exception\MorticianNotFoundException;
use Aspetos\Service\MorticianService;
use FOS\PermissionBundle\Model\PermissionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PermissionController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_MORTICIAN')")
 * @Route("/mortician/{morticianId}/permission")
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
            'entityService'     => 'aspetos.service.user',
            'entityFormType'    => 'aspetos_admin_form_mortician_permission',
            'formTemplate'      => 'AspetosAdminBundle:Layout:form_modal.html.twig',
            'icon'              => 'fa fa-lock',
            'title'             => 'Permission',
            'redirectRoute'     => 'aspetos_admin_dashboard_closemodal',
            'gridService'       => null
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * Edit action
     *
     * @ParamConverter("crudObject", class="Model:MorticianUser")
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     *
     * @Security("is_granted('mortician.user.edit', mortician)")
     *
     * @Route("/edit/{id}")
     *
     * @param Mortician     $mortician
     * @param MorticianUser $crudObject
     * @param Request       $request
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Mortician $mortician, MorticianUser $crudObject, Request $request)
    {
        return $this->formHandler($crudObject, $request, false, array(
            'action' => $this->generateUrl('aspetos_admin_mortician_permission_edit',
                array(
                    'morticianId' => $mortician->getId(),
                    'id'          => $crudObject->getId()
                )
            )
        ));
    }
}
