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

use Aspetos\Bundle\AdminBundle\Controller\CrudController;
use Aspetos\Model\Entity\Cemetery;
use Aspetos\Model\Entity\Mortician;
use Aspetos\Model\Entity\MorticianUser;
use Aspetos\Model\Entity\Supplier;
use Aspetos\Service\Exception\MorticianNotFoundException;
use Aspetos\Service\MorticianService;
use Aspetos\Service\SupplierService;
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
 * Class CemeteryController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_MORTICIAN')")
 * @Route("/mortician/{morticianId}/cemetery")
 */
class CemeteryController extends CrudController
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
            'entityFormType'    => 'aspetos_admin_form_supplier_cemetery',
            'formTemplate'      => 'AspetosAdminBundle:Layout:form_modal.html.twig',
            'icon'              => 'asp asp-grave',
            'title'             => 'Cemetery',
            'redirectRoute'     => 'aspetos_admin_dashboard_closemodal',
            'gridService'       => 'aspetos.admin.grid.mortician.cemetery',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * @Route("/add")
     * @Method({"GET", "POST"})
     *
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     * @Security("is_granted('mortician.cemetery.add', mortician)")
     *
     * @param Mortician $mortician
     * @param Request   $request
     *
     * @Template()
     *
     * @return RedirectResponse
     */
    public function addAction(Mortician $mortician, Request $request)
    {
        if ($request->isMethod('POST')) {
            $cemeteries = $request->get('cemeteries');
            foreach ($cemeteries as $cemetery) {
                $this->getService()->addCemeteryById($mortician, $cemetery);
            }

            $this->getService()->flush();

            return $this->redirectToRoute('aspetos_admin_dashboard_closemodal');
        }

        return array('mortician' => $mortician);
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
     * @Security("is_granted('mortician.cemetery.create', mortician)")
     *
     * @return RedirectResponse|Response
     */
    public function createForMorticianAction(Mortician $mortician, Request $request)
    {
        $object = new Cemetery();
        $object->addMortician($mortician);

        $return = $this->formHandler($object, $request, true, array(
            'action' => $this->generateUrl('aspetos_admin_mortician_supplier_create', array(
                'morticianId' => $mortician->getId()
            ))
        ));

        if ($return instanceof RedirectResponse) {
            $object->propose();
            $this->getService()->flush($object);
        }

        return $return;
    }

    /**
     * @param Mortician $mortician
     * @param Request   $request
     *
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     * @Security("is_granted('mortician.cemetery.add', mortician)")
     * @Route("/search")
     * @return JsonResponse
     */
    public function findCemeteriesDataAction(Mortician $mortician, Request $request)
    {
        /** @var SupplierService $service */
        $service = $this->get('aspetos.service.cemetery');
        $data = $service->findAllActiveAsArray($request->get('q'));
        $return = array();

        foreach ($data as $group => $cemeteries) {
            $sData = array('text' => $group);

            foreach ($cemeteries as $cemetery) {
                $sData['children'][] = array(
                    'id'   => $cemetery['id'],
                    'text' =>  $cemetery['name'].', '.$cemetery['address']['region']['country'].'-'.$cemetery['address']['zipcode'].' '.$cemetery['address']['city']
                );
            }
            $return[] = $sData;
        }

        return new JsonResponse(array('data' => $return));
    }

    /**
     * @Route("/delete/{id}")
     * @ParamConverter("crudObject", class="Model:Cemetery")
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     *
     * @Security("is_granted('mortician.cemetery.remove', mortician)")
     * @Method({"GET", "DELETE"})
     *
     * @param Mortician $mortician
     * @param Cemetery  $crudObject
     *
     * @return RedirectResponse
     */
    public function deleteAction(Mortician $mortician, Cemetery $crudObject)
    {
        if ($mortician->getCemeteries()->contains($crudObject)) {
            $mortician->removeCemetery($crudObject);
        }
        $this->getService()->flush();

        return $this->redirectToRoute('aspetos_admin_mortician_mortician_detail', array('id' => $mortician->getId()));
    }
}
