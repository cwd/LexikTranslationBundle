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
use Aspetos\Model\Entity\Candle;
use Aspetos\Model\Entity\Obituary;
use Aspetos\Model\Entity\Mortician;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use JMS\SecurityExtraBundle\Annotation\Secure;
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
 * @Route("/mortician/{morticianId}/obituary/{obituaryId}/candle")
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
            'entityFormType' => 'aspetos_admin_form_mortician_obituary',
            'gridService'    => 'aspetos.admin.grid.mortician.obituary.candle',
            'icon'           => 'fa fa-fire',
            'redirectRoute'  => 'aspetos_admin_mortician_mortician_detail',
            'title'          => 'Candle',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * Edit action
     * @Route("/edit/{id}")
     * @Method({"GET", "POST"})
     *
     * @param Candle    $crudObject
     * @param Obituary  $obituary
     * @param Mortician $mortician
     * @param Request   $request
     *
     * @ParamConverter("crudObject", class="Model:Candle")
     * @ParamConverter("obituary", class="Model:Obituary", options={"id" = "obituaryId"})
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     * @Security("is_granted('mortician.obituary.candle.edit', mortician)")
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Candle $crudObject, Obituary $obituary, Mortician $mortician, Request $request)
    {
        $this->setRuntimeOption('redirectParameter', array('id' => $mortician->getId()));

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
     * @Security("is_granted('mortician.obituary.candle.create', mortician)")
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Obituary $obituary, Mortician $mortician, Request $request)
    {
        $this->setRuntimeOption('redirectParameter', array('id' => $mortician->getId()));

        $object = $this->getNewEntity();
        $object->setMortician($mortician)
               ->setCountry($mortician->getCountry());

        return $this->formHandler($object, $request, true);
    }

    /**
     * delete action
     * @Route("/delete/{id}")
     * @Method({"GET", "DELETE"})
     *
     * @param Candle    $crudObject
     * @param Obituary  $obituary
     * @param Mortician $mortician
     * @param Request   $request
     *
     * @ParamConverter("crudObject", class="Model:Supplier")
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     * @ParamConverter("obituary", class="Model:Obituary", options={"id" = "obituaryId"})
     * @Security("is_granted('mortician.obituary.candle.delete', mortician)")
     *
     * @return RedirectResponse
     */
    public function deleteAction(Candle $crudObject, Obituary $obituary, Mortician $mortician, Request $request)
    {
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
     * @Security("is_granted('mortician.obituary.candle.view', mortician)")
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
