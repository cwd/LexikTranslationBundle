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
use Aspetos\Model\Entity\Condolence;
use Aspetos\Model\Entity\Obituary;
use Aspetos\Model\Entity\Mortician;
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
 * Class Mortician/CondolenceController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller\Mortician
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_MORTICIAN')")
 * @Route("/mortician/{morticianId}/obituary/{obituaryId}/condolence")
 */
class CondolenceController extends BaseController
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
            'entityService'  => 'aspetos.service.condolence',
            'entityFormType' => 'aspetos_admin_form_mortician_condolence',
            'gridService'    => 'aspetos.admin.grid.mortician.obituary.condolence',
            'icon'           => 'fa fa-fire',
            'redirectRoute'  => 'aspetos_admin_mortician_condolence_list',
            'title'          => 'Condolence',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * Block action
     * @Route("/block/{id}")
     * @Method({"GET"})
     *
     * @param Condolence $crudObject
     * @param Mortician  $mortician
     * @param Request    $request
     *
     * @ParamConverter("crudObject", class="Model:Condolence")
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     * @Security("is_granted('mortician.obituary.condolence.edit', mortician)")
     *
     * @return RedirectResponse|Response
     */
    public function blockAction(Condolence $crudObject, Mortician $mortician, Request $request)
    {
        $success = false;

        try {
            $crudObject->block();
            $this->getService()->flush($crudObject);
            $success = true;
            $message = 'Condolence got blocked';
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return new JsonResponse(array(
            'success' => $success,
            'message' => $message,
        ));
    }

    /**
     * unblock action
     * @Route("/unblock/{id}")
     * @Method({"GET"})
     *
     * @param Condolence $crudObject
     * @param Mortician  $mortician
     * @param Request    $request
     *
     * @ParamConverter("crudObject", class="Model:Condolence")
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     * @Security("is_granted('mortician.obituary.condolence.edit', mortician)")
     *
     * @return RedirectResponse|Response
     */
    public function unblockAction(Condolence $crudObject, Mortician $mortician, Request $request)
    {
        $success = false;

        try {
            $crudObject->activate();
            $this->getService()->flush($crudObject);
            $success = true;
            $message = 'Condolence got unblocked';
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return new JsonResponse(array(
            'success' => $success,
            'message' => $message,
        ));
    }

    /**
     * Edit action
     * @Route("/edit/{id}")
     * @Method({"GET", "POST"})
     *
     * @param Condolence $crudObject
     * @param Obituary   $obituary
     * @param Mortician  $mortician
     * @param Request    $request
     *
     * @ParamConverter("crudObject", class="Model:Condolence")
     * @ParamConverter("obituary", class="Model:Obituary", options={"id" = "obituaryId"})
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     * @Security("is_granted('mortician.obituary.condolence.edit', mortician)")
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Condolence $crudObject, Obituary $obituary, Mortician $mortician, Request $request)
    {
        $this->setRuntimeOption('redirectParameter', array(
            'id' => $crudObject->getId(),
            'morticianId' => $mortician->getId(),
            'obituaryId' => $obituary->getId(),
        ));

        $result = $this->formHandler($crudObject, $request, false);
        if ($result instanceof RedirectResponse && $request->get('target', null) == 'self') {
            return $this->redirectToRoute('aspetos_admin_mortician_mortician_detail', array('id' => $mortician->getId()));
        }

        return $result;
    }

    /**
     * create action
     * @Route("/create")
     * @Method({"GET", "POST"})
     *
     * @param Obituary  $obituary
     * @param Mortician $mortician
     * @param Request   $request
     *
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     * @ParamConverter("obituary", class="Model:Obituary", options={"id" = "obituaryId"})
     * @Security("is_granted('mortician.obituary.condolence.create', mortician)")
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Obituary $obituary, Mortician $mortician, Request $request)
    {
        $this->setRuntimeOption('redirectParameter', array(
            'id' => $obituary->getId(),
            'morticianId' => $mortician->getId(),
            'obituaryId' => $obituary->getId(),
        ));

        $object = $this->getNewEntity();
        $object->setObituary($obituary)
               ->setState('inactive');

        return $this->formHandler($object, $request, true);
    }

    /**
     * delete action
     * @Route("/delete/{id}")
     * @Method({"GET", "DELETE"})
     *
     * @param Condolence $crudObject
     * @param Obituary   $obituary
     * @param Mortician  $mortician
     * @param Request    $request
     *
     * @ParamConverter("crudObject", class="Model:Condolence")
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     * @ParamConverter("obituary", class="Model:Obituary", options={"id" = "obituaryId"})
     * @Security("is_granted('mortician.obituary.condolence.delete', mortician)")
     *
     * @return RedirectResponse
     */
    public function deleteAction(Condolence $crudObject, Obituary $obituary, Mortician $mortician, Request $request)
    {
        $this->setRuntimeOption('redirectParameter', array(
            'id' => $crudObject->getId(),
            'morticianId' => $mortician->getId(),
            'obituaryId' => $obituary->getId(),
        ));

        return $this->deleteHandler($crudObject, $request);
    }

    /**
     * @param Mortician $mortician
     * @param Obituary  $obituary
     *
     * @Route("/list")
     * @Route("/")
     * @Template()
     *
     * @ParamConverter("obituary", class="Model:Obituary", options={"id" = "obituaryId"})
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     * @Security("is_granted('mortician.obituary.condolence.view', mortician)")
     *
     * @return array
     */
    public function listAction(Mortician $mortician, Obituary $obituary)
    {
        $grid = $this->getGrid();
        $grid->setObituary($obituary);
        $grid->get();

        return array(
            'icon'      => $this->getOption('icon'),
            'obituary'  => $obituary,
            'mortician' => $mortician
        );
    }

    /**
     * Grid action
     * @param Obituary $obituary
     *
     * @ParamConverter("obituary", class="Model:Obituary", options={"id" = "obituaryId"})
     *
     * @Route("/grid")
     * @return Response
     */
    public function ajaxGridAction(Obituary $obituary)
    {
        $grid = $this->getGrid();
        $grid->setObituary($obituary);

        return $grid->get()->execute();
    }
}
