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
use Aspetos\Model\Entity\ProductCategory;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 * @Route("/product/category")
 */
class CategoryController extends CrudController
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
            'entityService'  => 'aspetos.service.product.category',
            'entityFormType' => 'aspetos_admin_form_product_category',
            'gridService'    => 'aspetos.admin.grid.product.category',
            'icon'           => 'fa fa-puzzle-piece',
            'redirectRoute'  => 'aspetos_admin_product_category_list',
            'title'          => 'Product Category',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * @Route("/detail/{id}/tab/{category}")
     *
     * @ParamConverter("crudObject", class="Model:ProductCategory")
     *
     * @param ProductCategory $crudObject
     * @param Request         $request
     *
     * @return Response
     */
    public function tabAction(ProductCategory $crudObject, Request $request)
    {
        $category = $request->get('category');

        switch ($category) {
            case 'profile':
                $template = 'tab_profile.html.twig';
                break;
            case 'user':
                $template = 'tab_user.html.twig';
                break;
            default:
                $template = '404';
        }

        return $this->render('AspetosAdminBundle:Product/Category:' . $template, array('crudObject' => $crudObject));
    }

    /**
     * @Route("/detail/{id}")
     * @Template()
     * @ParamConverter("crudObject", class="Model:ProductCategory")
     *
     * @param ProductCategory $crudObject
     *
     * @return array
     */
    public function detailAction(ProductCategory $crudObject)
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
     * @ParamConverter("crudObject", class="Model:ProductCategory")
     * @Route("/edit/{id}")
     *
     * @param ProductCategory $crudObject
     * @param Request         $request
     *
     * @return RedirectResponse|Response
     */
    public function editAction(ProductCategory $crudObject, Request $request)
    {
        $result = $this->formHandler($crudObject, $request, false);
        if ($result instanceof RedirectResponse && $request->get('target', null) == 'self') {
            return $this->redirectToRoute('aspetos_admin_product_category_detail', array('id' => $crudObject->getId()));
        }

        return $result;
    }

    /**
     * @Route("/delete/{id}")
     * @ParamConverter("crudObject", class="Model:ProductCategory")
     * @Method({"GET", "DELETE"})
     *
     * @param ProductCategory $crudObject
     * @param Request         $request
     *
     * @return RedirectResponse
     */
    public function deleteAction(ProductCategory $crudObject, Request $request)
    {
        return $this->deleteHandler($crudObject, $request);
    }

    /**
     * @Secure("ROLE_ADMIN")
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
     * @Secure("ROLE_ADMIN")
     * @Route("/grid")
     * @return Response
     */
    public function gridAction()
    {
        return parent::gridAction();
    }
}
