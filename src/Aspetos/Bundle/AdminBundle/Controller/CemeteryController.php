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

use Aspetos\Bundle\AdminBundle\Forms\CemeteryAdministrationType;
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
class CemeteryController extends CwdController
{
    /**
     * @param Cemetery $cemetery
     *
     * @Route("/detail/{id}")
     * @Template()
     * @ParamConverter("client", class="Model:Cemetery")
     *
     * @return array
     */
    public function detailAction(Cemetery $cemetery)
    {
        return array("cemetery" => $cemetery);
    }

    /**
     * @param Request $request
     *
     * @Route("/create")
     * @Method({"GET", "POST"})
     * @return array
     */
    public function createAction(Request $request)
    {
        $object = $this->getService()->getNew();

        return $this->formHandler($object, $request, true);
    }

    /**
     * Edit action
     *
     * @param Cemetery $cemetery
     * @param Request  $request
     *
     * @ParamConverter("cemetery", class="Model:Cemetery")
     * @Route("/edit/{id}")
     * @Template()
     * @return                     array
     */
    public function editAction(Cemetery $cemetery, Request $request)
    {
        return $this->formHandler($cemetery, $request);
    }

    /**
     * @param Cemetery $object
     * @param Request  $request
     * @param bool     $persist
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    protected function formHandler(Cemetery $object, Request $request, $persist = false)
    {
        $form = $this->createForm('aspetos_admin_form_cemetery', $object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($persist) {
                    $this->getService()->persist($object);
                }
                $this->getService()->getEm()->flush();

                $this->flashSuccess('Data successfully saved');

                return $this->redirect($this->generateUrl('aspetos_admin_cemetery_list'));
            } catch (\Exception $e) {
                $this->flashError('Error while saving Data: '.$e->getMessage());
            }

        }

        return $this->render(
            'AspetosAdminBundle:Layout:form.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'Cemetery',
            'icon'  => 'fa  fa-tag'
            )
        );
    }

    /**
     * @param Cemetery $cemetery
     * @param Request  $request
     *
     * @Route("/delete/{id}")
     * @ParamConverter("cemetery", class="Model:Cemetery")
     * @Method({"GET", "DELETE"})
     * @return Response
     */
    public function deleteAction(Cemetery $cemetery, Request $request)
    {
        try {
            $this->getService()->getEm()->remove($cemetery);
            $this->getService()->flush();
            $this->flashSuccess('Data successfully removed');
        } catch (CemeteryNotFoundException $e) {
            $this->flashError('Object with this ID not found ('.$request->get('id').')');
        } catch (\Exception $e) {
            $this->flashError('Unexpected Error: '.$e->getMessage());
        }

        return $this->redirect($this->generateUrl('aspetos_admin_cemetery_list'));

        return new Response();
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
        $this->get('aspetos.admin.grid.cemetery');

        return array();
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Grid action
     *
     * @Route("/grid")
     * @return         Response
     */
    public function gridAction()
    {
        return $this->get('aspetos.admin.grid.cemetery')->execute();
    }

    /**
     * @return CemeteryService
     */
    protected function getService()
    {
        return $this->get('aspetos.service.cemetery');
    }
}
