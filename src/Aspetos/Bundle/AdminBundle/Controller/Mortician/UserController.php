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
use FOS\UserBundle\Model\UserInterface;
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
 * Class UserController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_MORTICIAN')")
 * @Route("/mortician/{morticianId}/user")
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
            'entityFormType'    => 'aspetos_admin_form_mortician_user',
            'formTemplate'      => 'AspetosAdminBundle:Layout:form_modal.html.twig',
            'icon'              => 'fa fa-user',
            'title'             => 'User',
            'redirectRoute'     => 'aspetos_admin_dashboard_closemodal',
            'gridService'       => null
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * @Route("/create")
     * @Method({"GET", "POST"})
     *
     * @param Mortician $mortician
     * @param Request   $request
     *
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     *
     * @Security("is_granted('mortician.user.create', mortician)")
     *
     * @return RedirectResponse|Response
     */
    public function createForMorticianAction(Mortician $mortician, Request $request)
    {
        $object = new MorticianUser();
        $object->setMortician($mortician);

        return $this->formHandler($object, $request, true, array(
            'action' => $this->generateUrl('aspetos_admin_mortician_user_create', array(
                'morticianId' => $mortician->getId()
            ))
        ));
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
            'action' => $this->generateUrl('aspetos_admin_mortician_user_edit', array(
                'morticianId' => $mortician->getId(),
                'id'          => $crudObject->getId()
            ))
        ));
    }

    /**
     * @Route("/delete/{id}")
     * @ParamConverter("crudObject", class="Model:MorticianUser")
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     *
     * @Security("is_granted('mortician.user.delete', mortician)")
     * @Method({"GET", "DELETE"})
     *
     * @param Mortician     $mortician
     * @param MorticianUser $crudObject
     * @param Request       $request
     *
     * @return RedirectResponse
     */
    public function deleteAction(Mortician $mortician, MorticianUser $crudObject, Request $request)
    {
        return $this->deleteHandler($crudObject, $request);
    }
}
