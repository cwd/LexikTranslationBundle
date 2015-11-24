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
 * Class SupplierController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_MORTICIAN')")
 * @Route("/mortician/{morticianId}/supplier")
 */
class SupplierController extends CrudController
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
            'entityFormType'    => 'aspetos_admin_form_supplier_supplier',
            'formTemplate'      => 'AspetosAdminBundle:Layout:form_modal.html.twig',
            'icon'              => 'fa fa-truck',
            'title'             => 'Supplier',
            'redirectRoute'     => 'aspetos_admin_dashboard_closemodal',
            'gridService'       => 'aspetos.admin.grid.mortician.supplier',
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
        $object = new Supplier();
        $object->addMortician($mortician);

        $return = $this->formHandler($object, $request, true, array(
            'action' => $this->generateUrl('aspetos_admin_mortician_supplier_create',
                array(
                    'morticianId' => $mortician->getId()
                )
            )
        ));

        if ($return instanceof RedirectResponse) {
            $object->propose();
            $this->getService()->flush($object);
        }

        return $return;
    }

    /**
     * @Route("/add")
     * @Method({"GET", "POST"})
     *
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     * @Security("is_granted('mortician.supplier.add', mortician)")
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
            $suppliers = $request->get('suppliers');
            foreach ($suppliers as $supplier) {
                $this->getService()->addSupplierById($mortician, $supplier);
            }

            $this->getService()->flush();

            return $this->redirectToRoute('aspetos_admin_dashboard_closemodal');
        }

        return array('mortician' => $mortician);
    }

    /**
     * @param Mortician $mortician
     * @param Request   $request
     *
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     * @Security("is_granted('mortician.supplier.add', mortician)")
     * @Route("/search")
     * @return JsonResponse
     */
    public function findSuppliersDataAction(Mortician $mortician, Request $request)
    {
        /** @var SupplierService $service */
        $service = $this->get('aspetos.service.supplier');
        $data = $service->findAllActiveAsArray($request->get('q'));
        $return = array();
        foreach ($data as $group => $suppliers) {
            $sData = array('text' => $group);

            // {{ supplier.name }}, {{ supplier.country }} - {{ supplier.address.zipcode }} {{ supplier.address.city }}, {{ supplier.address.district.name }}
            foreach ($suppliers as $supplier) {
                $sData['children'][] = array(
                    'id'   => $supplier['id'],
                    'text' =>  $supplier['name'].', '.$supplier['country'].'-'.$supplier['address']['zipcode'].' '.$supplier['address']['city'].', '.$supplier['address']['district']['name'],
                );

                $return[] = $sData;
            }
        }

        return new JsonResponse(array('data' => $return));
    }

    /**
     * @Route("/delete/{id}")
     * @ParamConverter("crudObject", class="Model:Supplier")
     * @ParamConverter("mortician", class="Model:Mortician", options={"id" = "morticianId"})
     *
     * @Security("is_granted('mortician.supplier.remove', mortician)")
     * @Method({"GET", "DELETE"})
     *
     * @param Mortician $mortician
     * @param Supplier  $crudObject
     *
     * @return RedirectResponse
     */
    public function deleteAction(Mortician $mortician, Supplier $crudObject)
    {
        if ($mortician->getSuppliers()->contains($crudObject)) {
            $mortician->removeSupplier($crudObject);
        }
        $this->getService()->flush();

        return $this->redirectToRoute('aspetos_admin_mortician_mortician_detail', array('id' => $mortician->getId()));
    }
}
