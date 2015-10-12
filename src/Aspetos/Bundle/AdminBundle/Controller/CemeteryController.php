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

use Aspetos\Model\Entity\Cemetery;
use Aspetos\Model\Entity\CemeteryAdministration;
use Aspetos\Service\Exception\CemeteryNotFoundException;
use Aspetos\Service\CemeteryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Cwd\GenericBundle\Controller\GenericController as CwdController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Url;

/**
 * Class CemeteryController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 * @Route("/cemetery")
 */
class CemeteryController extends BaseController
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
            'entityService'     => 'aspetos.service.cemetery',
            'entityFormType'    => 'aspetos_admin_form_cemetery',
            'gridService'       => 'aspetos.admin.grid.cemetery',
            'icon'              => 'asp asp-grave',
            'redirectRoute'     => 'aspetos_admin_cemetery_list',
            'title'             => 'Cemetery',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * @Route("/detail/{id}")
     * @Template()
     * @ParamConverter("crudObject", class="Model:Cemetery")
     *
     * @param Cemetery $crudObject
     *
     * @return array
     */
    public function detailAction(Cemetery $crudObject)
    {
        return array("crudObject" => $crudObject);
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
        $object = new Cemetery();

        return $this->formHandler($object, $request, true);
    }

    /**
     * Edit action
     *
     * @ParamConverter("crudObject", class="Model:Cemetery")
     * @Route("/edit/{id}")
     *
     * @param Cemetery $crudObject
     * @param Request  $request
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Cemetery $crudObject, Request $request)
    {
        return $this->formHandler($crudObject, $request, false);
    }

    /**
     * @Route("/delete/{id}")
     * @ParamConverter("crudObject", class="Model:Cemetery")
     * @Method({"GET", "DELETE"})
     *
     * @param Cemetery $crudObject
     * @param Request  $request
     *
     * @return RedirectResponse
     */
    public function deleteAction(Cemetery $crudObject, Request $request)
    {
        return $this->deleteHandler($crudObject, $request);
    }
}
