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
use Aspetos\Model\Entity\Supplier;
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
            'entityService'  => 'aspetos.service.mortician',
            'entityFormType' => 'aspetos_admin_form_mortician_obituary',
            'gridService'    => 'aspetos.admin.grid.mortician.obituary',
            'icon'           => 'fa fa-bookmark',
            'redirectRoute'  => 'aspetos_admin_mortician_supplier_list',
            'title'          => 'Obituary',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * Edit action
     *
     * @ParamConverter("crudObject", class="Model:Supplier")
     * @Route("/edit/{id}")
     *
     * @param Supplier $crudObject
     * @param Request  $request
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Supplier $crudObject, Request $request)
    {
        $result = $this->formHandler($crudObject, $request, false);
        if ($result instanceof RedirectResponse && $request->get('target', null) == 'self') {
            return $this->redirectToRoute('aspetos_admin_supplier_supplier_detail', array('id' => $crudObject->getId()));
        }

        return $result;
    }

    /**
     * @Route("/delete/{id}")
     * @ParamConverter("crudObject", class="Model:Supplier")
     * @Method({"GET", "DELETE"})
     *
     * @param Supplier $crudObject
     * @param Request  $request
     *
     * @return RedirectResponse
     */
    public function deleteAction(Supplier $crudObject, Request $request)
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
     *
     */
    public function candlesAction()
    {
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
