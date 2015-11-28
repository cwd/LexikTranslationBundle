<?php
/*
 * This file is part of AspetosAdminBundle
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Aspetos\Bundle\AdminBundle\Controller\User;

use Aspetos\Bundle\AdminBundle\Controller\CrudController;
use Aspetos\Model\Entity\Admin;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Aspetos\Service\UserInterface as AspetosUserInterface;

/**
 * Class UserController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 * @Route("/user")
 */
class UserController extends CrudController
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
            'entityService'     => 'aspetos.service.user',
            'entityFormType'    => 'aspetos_admin_form_user_admin',
            'gridService'       => 'aspetos.admin.grid.user',
            'icon'              => 'fa fa-users',
            'redirectRoute'     => 'aspetos_admin_user_user_list',
            'title'             => 'User',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * @Route("/detail/{id}")
     * @Template()
     * @ParamConverter("crudObject", class="Model:BaseUser")
     *
     * @param AspetosUserInterface $crudObject
     *
     * @return array
     */
    public function detailAction(AspetosUserInterface $crudObject)
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
        $object = new Admin();

        return $this->formHandler($object, $request, true);
    }

    /**
     * Edit action
     *
     * @ParamConverter("crudObject", class="Model:BaseUser")
     * @Route("/edit/{id}")
     *
     * @param AspetosUserInterface $crudObject
     * @param Request              $request
     *
     * @return RedirectResponse|Response
     */
    public function editAction(AspetosUserInterface $crudObject, Request $request)
    {
        return $this->formHandler($crudObject, $request, false);
    }

    /**
     * @Route("/delete/{id}")
     * @ParamConverter("crudObject", class="Model:BaseUser")
     * @Method({"GET", "DELETE"})
     *
     * @param AspetosUserInterface $crudObject
     * @param Request              $request
     *
     * @return RedirectResponse
     */
    public function deleteAction(AspetosUserInterface $crudObject, Request $request)
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
        $this->getGrid();

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
     * @return Response
     */
    public function gridAction()
    {
        return $this->getGrid()->execute();
    }

    /**
     * @param misc    $crudObject
     * @param Request $request
     * @param bool    $persist
     * @param array   $formOptions
     *
     * @return RedirectResponse|Response
     */
    protected function formHandler($crudObject, Request $request, $persist = false, $formOptions = array())
    {
        $this->checkModelClass($crudObject);
        $form = $this->createForm($this->getOption('entityFormType'), $crudObject, $formOptions);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($persist) {
                    // We need to flush the user object first, because of
                    // Entity of type Admin|Customer|MorticianUser|SupplierUser has identity through a foreign entity BaseUser,
                    // however this entity has no identity itself.
                    $this->getService()->persist($crudObject->getUser());
                    $this->getService()->getEm()->flush($crudObject->getUser());

                    $this->getService()->persist($crudObject);

                }

                $this->getService()->flush();

                $this->flashSuccess($this->getOption('successMessage'));

                return $this->redirect($this->generateUrl($this->getOption('redirectRoute')));
            } catch (\Exception $e) {
                $this->flashError('Error while saving Data: '.$e->getMessage());
            }
        }

        return $this->render($this->getOption('formTemplate'), array(
            'form'  => $form->createView(),
            'title' => $this->getOption('title'),
            'icon'  => $this->getOption('icon'),
        ));
    }
}
