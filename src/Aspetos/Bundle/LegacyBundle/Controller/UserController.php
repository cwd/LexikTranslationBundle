<?php
/*
 * This file is part of AspetosLegacyBundle
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Aspetos\Bundle\LegacyBundle\Controller;

use Aspetos\Bundle\LegacyBundle\Model\Entity\User as User;
use Aspetos\Service\Exception\UserNotFoundException;
use Aspetos\Service\UserService;
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
 * Class UserController
 *
 * @package AspetosLegacyBundle\Bundle\LegacyBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 * @Route("/user")
 */
class UserController extends CwdController
{
    /**
     * @param User $user
     *
     * @Route("/detail/{id}")
     * @Template()
     * @ParamConverter("client", class="Legacy:User")
     *
     * @return array
     */
    public function detailAction(User $user)
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
        $object = $this->getService()->getNew();

        return $this->formHandler($object, $request, true);
    }

    /**
     * Edit action
     * @param User    $user
     * @param Request $request
     *
     * @ParamConverter("user", class="Legacy:User")
     * @Route("/edit/{id}")
     * @Template()
     * @return array
     */
    public function editAction(User $user, Request $request)
    {
        return $this->formHandler($user, $request);
    }

    /**
     * @param User $object
     * @param Request  $request
     * @param bool     $persist
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    protected function formHandler(User $object, Request $request, $persist = false)
    {
        $form = $this->createForm('aspetos_legacy_form_user', $object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($persist) {
                    $this->getService()->persist($object);
                }
                $this->getService()->getEm()->flush();

                $this->flashSuccess('Data successfully saved');

                return $this->redirect($this->generateUrl('aspetos_legacy_user_list'));
            } catch (\Exception $e) {
                $this->flashError('Error while saving Data: '.$e->getMessage());
            }

        }

        return $this->render('AspetosAdminBundle:Layout:form.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'User',
            'icon'  => 'fa  fa-tag'
        ));
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @Route("/delete/{id}")
     * @ParamConverter("user", class="Legacy:User")
     * @Method({"GET", "DELETE"})
     * @return Response
     */
    public function deleteAction(User $user, Request $request)
    {
        try {
            $this->getService()->getEm()->remove($user);
            $this->getService()->flush();
            $this->flashSuccess('Data successfully removed');
        } catch (ClientNotFoundException $e) {
            $this->flashError('Object with this ID not found ('.$request->get('id').')');
        } catch (\Exception $e) {
            $this->flashError('Unexpected Error: '.$e->getMessage());
        }

        return $this->redirect($this->generateUrl('aspetos_legacy_user_list'));

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
        $this->get('aspetos.legacy.grid.user');

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
        return $this->get('aspetos.legacy.grid.user')->execute();
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @ParamConverter("user", class="Legacy:User")
     * @Method({"POST"})
     * @Route("/toggle/{id}")
     * @return JsonResponse
     */
    public function toogleBlockAction(User $user, Request $request)
    {
        $state = $request->get('state');

        try {
            $state = ($state == 'true') ? 1 : 0;
            $user->setBlock($state);
            $this->getService()->flush();

        } catch (\Exception $e) {
            return new JsonResponse(array('success' => false, 'message' => $e->getMessage()));
        }

        return new JsonResponse(array('success' => true, 'message' => ($state) ? 'User now blocked' : 'User now unblocked'));
    }

    /**
     * @return UserService
     */
    protected function getService()
    {
        return $this->get('aspetos.service.legacy.user');
    }
}
