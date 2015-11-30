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

use Aspetos\Bundle\AdminBundle\Controller\BaseController;
use Aspetos\Model\Entity\Candle;
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
 * Class Mortician/CandleController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller\Mortician
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_MORTICIAN')")
 * @Route("/candle")
 */
class CandleController extends BaseController
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
            'entityService'  => 'aspetos.service.candle',
            'entityFormType' => 'aspetos_admin_form_candle',
            'gridService'    => 'aspetos.admin.grid.candle',
            'redirectRoute'  => 'aspetos_admin_candle_list',
            'title'          => 'Candle',
            'icon'           => 'fa fa-bookmark',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * Edit action
     * @Route("/edit/{id}")
     * @Method({"GET", "POST"})
     *
     * @param Candle  $crudObject
     * @param Request $request
     *
     * @ParamConverter("crudObject", class="Model:Candle")
     * Security("is_granted('mortician.candle.edit', crudObject.getObituary().getMortician())")
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Candle $crudObject, Request $request)
    {
        $this->setRuntimeOption('redirectParameter', array(
            'id' => $crudObject->getId(),
            'obituaryId' => $crudObject->getObituary()->getId(),
        ));

        return $this->formHandler($crudObject, $request, false);
    }

    /**
     * create action
     * @Route("/create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @Security("is_granted('mortician.candle.create', user.getMortician())")
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $object = $this->getNewEntity();
        if (!$this->isGranted('ROLE_ADMIN')) {
            $object->setMortician($this->getUser()->getMortician());
        }

        $this->setRuntimeOption('redirectParameter', array(
            'obituaryId' => $object->getObituary()->getId(),
        ));

        return $this->formHandler($object, $request, true);
    }

    /**
     * delete action
     * @Route("/delete/{id}")
     * @Method({"GET", "DELETE"})
     *
     * @param Candle  $crudObject
     * @param Request $request
     *
     * @ParamConverter("crudObject", class="Model:Supplier")
     * @Security("is_granted('mortician.candle.delete', crudObject.getObituary().getMortician())")
     *
     * @return RedirectResponse
     */
    public function deleteAction(Candle $crudObject, Request $request)
    {
        return $this->deleteHandler($crudObject, $request);
    }

    /**
     * @param Request $request
     *
     * @Route("/list")
     * @Route("/")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $morticianId = null;
        $mortician   = null;
        $obituaryId  = $request->get('obituaryId', null);
        $obituary    = null;

        if (!$this->isGranted('ROLE_ADMIN')) {
            $user = $this->getUser();
            $mortician = $user->getMortician();
            $morticianId = $mortician->getId();
        }

        $grid = $this->getGrid();

        if ($obituaryId !== null) {
            $obituary = $this->get('aspetos.service.obituary')->find($obituaryId);
            $grid->setObituary($obituary);
        }


        $grid->setMortician($mortician);
        $grid->get();

        return array(
            'icon' => $this->getOption('icon'),
            'morticianId' => $morticianId,
            'obituaryId'  => $obituaryId,
            'obituary'    => $obituary,
        );
    }

    /**
     * Grid action
     * @param Request $request
     *
     * @Route("/grid")
     * @return Response
     */
    public function ajaxGridAction(Request $request)
    {
        $mortician   = null;
        $obituaryId  = $request->get('obituaryId', null);

        if (!$this->isGranted('ROLE_ADMIN')) {
            $morticianId = $request->get('morticianId', null);
            $mortician = $this->get('aspetos.service.mortician')->find($morticianId);
        }

        $grid = $this->getGrid();

        if ($obituaryId !== null) {
            $grid->setObituary($this->get('aspetos.service.obituary')->find($obituaryId));
        }

        $grid->setMortician($mortician);

        return $grid->get()->execute();
    }

    /**
     * Block action
     * @Route("/block/{id}")
     * @Method({"GET"})
     *
     * @param Candle  $crudObject
     * @param Request $request
     *
     * @ParamConverter("crudObject", class="Model:Candle")
     * @Security("is_granted('mortician.obituary.candle.edit', crudObject.getObituary().getMortician())")
     *
     * @return RedirectResponse|Response
     */
    public function blockAction(Candle $crudObject, Request $request)
    {
        $success = false;

        try {
            $crudObject->block();
            $this->getService()->flush($crudObject);
            $success = true;
            $message = 'Candle got blocked';
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
     * @param Candle  $crudObject
     * @param Request $request
     *
     * @ParamConverter("crudObject", class="Model:Candle")
     * @Security("is_granted('mortician.obituary.candle.edit', crudObject.getObituary().getMortician())")
     *
     * @return RedirectResponse|Response
     */
    public function unblockAction(Candle $crudObject, Request $request)
    {
        $success = false;

        try {
            $crudObject->activate();
            $this->getService()->flush($crudObject);
            $success = true;
            $message = 'Candle got unblocked';
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return new JsonResponse(array(
            'success' => $success,
            'message' => $message,
        ));

    }

    /**
     * Get new entity provided by the service.
     *
     * @return mixed
     */
    protected function getNewEntity()
    {
        $entity = parent::getNewEntity();
        $entity->setCountry('AT');

        return $entity;
    }
}
