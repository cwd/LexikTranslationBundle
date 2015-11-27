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
 * Class Mortician/ObituaryController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller\Mortician
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_MORTICIAN')")
 * @Route("/mortician/{morticianId}/obituary")
 */
class ObituaryController extends BaseController
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
            'entityService'  => 'aspetos.service.obituary',
            'entityFormType' => 'aspetos_admin_form_mortician_obituary',
            'gridService'    => 'aspetos.admin.grid.mortician.obituary',
            'icon'           => 'fa fa-bookmark',
            'redirectRoute'  => 'aspetos_admin_mortician_mortician_detail',
            'redirectParameter' => '', // array('id' => $request->get('morticanId')); <--- ????????
            'title'          => 'Obituary',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * Edit action
     * @Route("/edit/{id}")
     * @Method({"GET", "POST"})
     *
     * @param Obituary  $crudObject
     * @param Mortician $mortician
     * @param Request   $request
     *
     * @ParamConverter("crudObject", class="Model:Obituary")
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     * @Security("is_granted('mortician.obituary.edit', mortician)")
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Obituary $crudObject, Mortician $mortician, Request $request)
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
     * @param Mortician $mortician
     * @param Request   $request
     *
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     * @Security("is_granted('mortician.obituary.create', mortician)")
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Mortician $mortician, Request $request)
    {
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
     * @param Obituary  $crudObject
     * @param Mortician $mortician
     * @param Request   $request
     *
     * @ParamConverter("crudObject", class="Model:Supplier")
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     * @Security("is_granted('mortician.obituary.delete', mortician)")
     *
     * @return RedirectResponse
     */
    public function deleteAction(Obituary $crudObject, Mortician $mortician, Request $request)
    {
        return $this->deleteHandler($crudObject, $request);
    }

    /**
     * @param Mortician $mortician
     *
     * @Route("/list")
     * @Route("/")
     * @Template()
     *
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     *
     * @return array
     */
    public function listAction(Mortician $mortician)
    {
        $grid = $this->getGrid();
        $grid->setMortician($mortician);
        $grid->get();

        return array(
            'icon' => $this->getOption('icon'),
            'mortician' => $mortician
        );
    }

    /**
     * Grid action
     * @param Mortician $mortician
     *
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     *
     * @Route("/grid")
     * @return Response
     */
    public function ajaxGridAction(Mortician $mortician)
    {
        $grid = $this->getGrid();
        $grid->setMortician($mortician);

        return $grid->get()->execute();
    }

    /**
     * @Route("/candles")
     * @Template()
     * @return array()
     */
    public function candlesAction()
    {
        // dummy
        return array();
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
