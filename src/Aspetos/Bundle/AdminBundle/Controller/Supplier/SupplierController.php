<?php
/*
 * This file is part of AspetosAdminBundle
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Aspetos\Bundle\AdminBundle\Controller\Supplier;

use Aspetos\Bundle\AdminBundle\Controller\BaseController;
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
 * Class SupplierController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 * @Route("/supplier")
 */
class SupplierController extends BaseController
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
            'entityService'  => 'aspetos.service.supplier.supplier',
            'entityFormType' => 'aspetos_admin_form_supplier_supplier',
            'gridService'    => 'aspetos.admin.grid.supplier.supplier',
            'icon'           => 'fa fa-truck',
            'redirectRoute'  => 'aspetos_admin_supplier_supplier_list',
            'title'          => 'Supplier',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * @Route("/detail/{id}/tab/{type}")
     *
     * @ParamConverter("crudObject", class="Model:Supplier")
     *
     * @param Supplier $crudObject
     * @param Request  $request
     *
     * @return Response
     */
    public function tabAction(Supplier $crudObject, Request $request)
    {
        $type = $request->get('type');

        switch ($type) {
            case 'profile':
                $template = 'tab_profile.html.twig';
                break;
            case 'user':
                $template = 'tab_user.html.twig';
                break;
            default:
                $template = '404';
        }

        return $this->render('AspetosAdminBundle:Supplier/Supplier:' . $template, array('crudObject' => $crudObject));
    }

    /**
     * @Route("/detail/{id}")
     * @Template()
     * @ParamConverter("crudObject", class="Model:Supplier")
     *
     * @param Supplier $crudObject
     *
     * @return array
     */
    public function detailAction(Supplier $crudObject)
    {
        return array(
            'crudObject' => $crudObject,
            'icon' => $this->getOption('icon'),
            'title' => $this->getOption('title')
        );
    }

    /**
     * @Route("/create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $object = new Supplier();
        $object->setCountry('AT');

        return $this->formHandler($object, $request, true);
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
     * @Route("/list")
     * @Route("/")
     * @Template()
     *
     * @return array
     */
    public function listAction()
    {
        return parent::listAction();
    }

    /**
     * Grid action
     *
     * @Route("/grid")
     * @return Response
     */
    public function gridAction()
    {
        return parent::gridAction();
    }
}
