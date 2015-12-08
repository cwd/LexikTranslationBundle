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
 * @Route("/obituary")
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
            'entityFormType' => 'aspetos_admin_form_obituary',
            'gridService'    => 'aspetos.admin.grid.obituary',
            'redirectRoute'  => 'aspetos_admin_obituary_list',
            'title'          => 'Obituary',
            'icon'           => 'fa fa-bookmark',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * Edit action
     * @Route("/edit/{id}")
     * @Method({"GET", "POST"})
     *
     * @param Obituary $crudObject
     * @param Request  $request
     *
     * @ParamConverter("crudObject", class="Model:Obituary")
     * @Security("crudObject.getMortician() == null or is_granted('mortician.obituary.edit', crudObject.getMortician())")
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Obituary $crudObject, Request $request)
    {
        return $this->formHandler($crudObject, $request, false);
    }

    /**
     * create action
     * @Route("/create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @Security("is_granted('mortician.obituary.create', user.getMortician())")
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $object = $this->getNewEntity();
        if (!$this->isGranted('ROLE_ADMIN')) {
            $object->setMortician($this->getUser()->getMortician());
        }

        return $this->formHandler($object, $request, true);
    }

    /**
     * delete action
     * @Route("/delete/{id}")
     * @Method({"GET", "DELETE"})
     *
     * @param Obituary $crudObject
     * @param Request  $request
     *
     * @ParamConverter("crudObject", class="Model:Supplier")
     * @Security("is_granted('mortician.obituary.delete', crudObject.getMortician())")
     *
     * @return RedirectResponse
     */
    public function deleteAction(Obituary $crudObject, Request $request)
    {
        return $this->deleteHandler($crudObject, $request);
    }

    /**
     * @Route("/list")
     * @Route("/")
     * @Template()
     *
     * Security("is_granted('mortician.obituary.view', mortician)")
     * @return array
     */
    public function listAction()
    {
        $morticianId = null;
        $mortician   = null;

        if (!$this->isGranted('ROLE_ADMIN')) {
            $user = $this->getUser();
            $mortician = $user->getMortician();
            $morticianId = $mortician->getId();
        }

        $grid = $this->getGrid();
        $grid->setMortician($mortician);
        $grid->get();

        return array(
            'icon' => $this->getOption('icon'),
            'morticianId' => $morticianId
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
        $mortician = null;

        if (!$this->isGranted('ROLE_ADMIN')) {
            $morticianId = $request->get('morticianId', null);
            $mortician = $this->get('aspetos.service.mortician')->find($morticianId);
        }

        $grid = $this->getGrid();
        $grid->setMortician($mortician);

        return $grid->get()->execute();
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
