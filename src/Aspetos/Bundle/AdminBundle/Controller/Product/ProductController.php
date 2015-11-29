<?php
/*
 * This file is part of AspetosAdminBundle
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Aspetos\Bundle\AdminBundle\Controller\Product;

use Aspetos\Bundle\AdminBundle\Controller\CrudController;
use Aspetos\Model\Entity\Product;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 * @Route("/product")
 */
class ProductController extends CrudController
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
            'entityService'  => 'aspetos.service.product.product',
            'entityFormType' => 'aspetos_admin_form_product_product',
            'gridService'    => 'aspetos.admin.grid.product',
            'icon'           => 'fa fa-cubes',
            'redirectRoute'  => 'aspetos_admin_product_product_list',
            'title'          => 'Product',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * @Route("/detail/{id}/tab/{type}")
     *
     * @ParamConverter("crudObject", class="Model:Product")
     *
     * @param Product $crudObject
     * @param Request $request
     *
     * @return Response
     */
    public function tabAction(Product $crudObject, Request $request)
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

        return $this->render('AspetosAdminBundle:Product/Product:' . $template, array('crudObject' => $crudObject));
    }

    /**
     * @Route("/detail/{id}")
     * @Template()
     * @ParamConverter("crudObject", class="Model:Product")
     *
     * @param Product $crudObject
     *
     * @return array
     */
    public function detailAction(Product $crudObject)
    {
        return array(
            'crudObject' => $crudObject,
            'icon' => $this->getOption('icon'),
            'title' => $this->getOption('title')
        );
    }

    /**
     * Edit action
     *
     * @ParamConverter("crudObject", class="Model:Product")
     * @Route("/edit/{id}")
     *
     * @param Product $crudObject
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Product $crudObject, Request $request)
    {
        $result = $this->formHandler($crudObject, $request, false);
        if ($result instanceof RedirectResponse && $request->get('target', null) == 'self') {
            return $this->redirectToRoute('aspetos_admin_product_product_detail', array('id' => $crudObject->getId()));
        }

        return $result;
    }

    /**
     * @Route("/delete/{id}")
     * @ParamConverter("crudObject", class="Model:Product")
     * @Method({"GET", "DELETE"})
     *
     * @param Product $crudObject
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function deleteAction(Product $crudObject, Request $request)
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
