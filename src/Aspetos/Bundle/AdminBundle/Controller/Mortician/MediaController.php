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
use Aspetos\Model\Entity\MorticianMedia;
use Aspetos\Model\Entity\MorticianUser;
use Aspetos\Service\Exception\MorticianNotFoundException;
use Aspetos\Service\MorticianService;
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
 * Class MediaController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_MORTICIAN')")
 * @Route("/mortician/{morticianId}/media")
 */
class MediaController extends BaseController
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
            'entityService'     => 'aspetos.service.mortician',
            'entityFormType'    => 'aspetos_admin_form_mortician_image',
            'formTemplate'      => 'AspetosAdminBundle:Layout:form_modal.html.twig',
            'icon'              => 'fa fa-image',
            'title'             => 'Image',
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
    public function createAction(Mortician $mortician, Request $request)
    {
        $object = new MorticianMedia();
        $object->setMortician($mortician);

        return $this->formHandler($object, $request, true, array(
            'action' => $this->generateUrl('aspetos_admin_mortician_media_create', array(
                'morticianId' => $mortician->getId()
            ))
        ));
    }

    /**
     * Edit action
     *
     * @ParamConverter("crudObject", class="Model:MorticianMedia")
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     *
     * @Security("is_granted('mortician.media', mortician)")
     *
     * @Route("/edit/{id}")
     *
     * @param Mortician      $mortician
     * @param MorticianMedia $crudObject
     * @param Request        $request
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Mortician $mortician, MorticianMedia $crudObject, Request $request)
    {
        return $this->formHandler($crudObject, $request, false, array(
            'action' => $this->generateUrl('aspetos_admin_mortician_media_edit', array(
                'morticianId' => $mortician->getId(),
                'id'          => $crudObject->getId()
            ))
        ));
    }

    /**
     * @Route("/delete/{id}")
     * @ParamConverter("crudObject", class="Model:MorticianMedia")
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     *
     * @Security("is_granted('mortician.media', mortician)")
     * @Method({"GET", "DELETE"})
     *
     * @param Mortician      $mortician
     * @param MorticianMedia $crudObject
     * @param Request        $request
     *
     * @return RedirectResponse
     */
    public function deleteAction(Mortician $mortician, MorticianMedia $crudObject, Request $request)
    {
        return $this->deleteHandler($crudObject, $request);
    }
}
