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

use Aspetos\Model\Entity\Admin;
use Aspetos\Model\Entity\User;
use Aspetos\Service\Exception\UserNotFoundException;
use Aspetos\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Cwd\GenericBundle\Controller\GenericController as CwdController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 * @Route("/user")
 */
class UserController extends CwdController
{

    /**
     * @param UserInterface $user
     *
     * @Route("/detail/{id}")
     * @Template()
     * @ParamConverter("client", class="Model:User")
     *
     * @return array
     */
    public function detailAction(UserInterface $user)
    {
        return array("user" => $user);
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
        $object = new Admin();

        return $this->formHandler($object, $request, true);
    }

    /**
     * Edit action
     * @param UserInterface $user
     * @param Request       $request
     *
     * @ParamConverter("user", class="Model:User")
     * @Route("/edit/{id}")
     * @Template()
     * @return array
     */
    public function editAction(UserInterface $user, Request $request)
    {
        return $this->formHandler($user, $request);
    }

    /**
     * @param UserInterface $object
     * @param Request       $request
     * @param bool          $persist
     * @param string        $formType
     * @param string        $title
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    protected function formHandler(UserInterface $object, Request $request, $persist = false, $formType = 'aspetos_admin_form_user_admin', $title = 'Admin')
    {
        $form = $this->createForm($formType, $object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($persist) {
                    $this->get('aspetos.service.handler.user')->createUser($object);
                } else {
                    $this->get('aspetos.service.handler.user')->editUser($object);
                }


                $this->flashSuccess('Data successfully saved');

                return $this->redirect($this->generateUrl('aspetos_admin_user_user_list'));
            } catch (\Exception $e) {
                $this->flashError('Error while saving Data: '.$e->getMessage());
            }

        }

        return $this->render('AspetosAdminBundle:Layout:form.html.twig', array(
            'form'  => $form->createView(),
            'title' => $title,
            'icon'  => 'fa fa-users'
        ));
    }

    /**
     * @param UserInterface $user
     * @param Request       $request
     *
     * @Route("/delete/{id}")
     * @ParamConverter("user", class="Model:User")
     * @Method({"GET", "DELETE"})
     * @return RedirectResponse
     */
    public function deleteAction(UserInterface $user, Request $request)
    {
        try {
            $this->get('neos.service.handler.user')->removeUser($user);
            $this->flashSuccess('Data successfully removed');
        } catch (UserNotFoundException $e) {
            $this->flashError('Object with this ID not found ('.$request->get('id').')');
        } catch (\Exception $e) {
            $this->flashError('Unexpected Error: '.$e->getMessage());
        }

        return $this->redirect($this->generateUrl('aspetos_admin_user_list'));
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
        $this->get('aspetos.admin.grid.user');

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
        return $this->get('aspetos.admin.grid.user')->execute();
    }

    /**
     * @return UserService
     */
    protected function getService()
    {
        return $this->get('aspetos.service.user');
    }
}
