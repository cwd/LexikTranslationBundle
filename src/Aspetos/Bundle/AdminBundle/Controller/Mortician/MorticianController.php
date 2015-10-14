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
use Aspetos\Service\Exception\MorticianNotFoundException;
use Aspetos\Service\MorticianService;
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
 * Class MorticianController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_MORTICIAN')")
 * @Route("/mortician")
 */
class MorticianController extends BaseController
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
            'entityFormType'    => 'aspetos_admin_form_mortician',
            'gridService'       => 'aspetos.admin.grid.mortician',
            'icon'              => 'asp asp-grave',
            'redirectRoute'     => 'aspetos_admin_mortician_list',
            'title'             => 'Mortician',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * @Security("is_granted('view', crudObject)")
     * @Route("/detail/{id}")
     * @Template()
     * @ParamConverter("crudObject", class="Model:Mortician")
     *
     * @param Mortician $crudObject
     *
     * @return array
     */
    public function detailAction(Mortician $crudObject)
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
    /*
    public function createAction(Request $request)
    {
        $object = new Mortician();

        return $this->formHandler($object, $request, true);
    }
    */

    /**
     * Edit action
     *
     * @ParamConverter("crudObject", class="Model:Mortician")
     * @Route("/edit/{id}")
     *
     * @param Mortician $crudObject
     * @param Request  $request
     *
     * @return RedirectResponse|Response
     */
    /*
    public function editAction(Mortician $crudObject, Request $request)
    {
        return $this->formHandler($crudObject, $request, false);
    }
    */

    /**
     * @Route("/delete/{id}")
     * @ParamConverter("crudObject", class="Model:Mortician")
     * @Method({"GET", "DELETE"})
     *
     * @param Mortician $crudObject
     * @param Request  $request
     *
     * @return RedirectResponse
     */
    /*
    public function deleteAction(Mortician $crudObject, Request $request)
    {
        return $this->deleteHandler($crudObject, $request);
    }
    */
}
