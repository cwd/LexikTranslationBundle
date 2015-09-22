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

use Aspetos\Bundle\LegacyBundle\Model\Entity\BookEntry;
use Aspetos\Service\Exception\BookEntryNotFoundException;
use Aspetos\Service\Legacy\BookEntryService;
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
 * Class CandleController
 *
 * @package AspetosLegacyBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 * @Route("/candle")
 */
class CandleController extends CwdController
{

    /**
     * @param BookEntry $bookentry
     *
     * @Route("/detail/{id}")
     * @Template()
     * @ParamConverter("client", class="Model:BookEntry")
     *
     * @return array
     */
    public function detailAction(BookEntry $bookentry)
    {
        return array("bookentry" => $bookentry);
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
     * @param BookEntry $bookentry
     * @param Request   $request
     *
     * @ParamConverter("bookentry", class="Legacy:BookEntry")
     * @Route("/edit/{id}/{obituary}", defaults={"obituary"=null})
     * @Route("/edit/{id}")
     * @Template()
     * @return array
     */
    public function editAction(BookEntry $bookentry, Request $request)
    {
        return $this->formHandler($bookentry, $request);
    }

    /**
     * @param BookEntry  $object
     * @param Request $request
     * @param bool    $persist
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    protected function formHandler(BookEntry $object, Request $request, $persist = false)
    {
        $form = $this->createForm('aspetos_legacy_form_bookentry', $object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($persist) {
                    $this->getService()->persist($object);
                }
                $this->getService()->getEm()->flush();

                $this->flashSuccess('Data successfully saved');

                return $this->redirect($this->generateUrl('aspetos_legacy_candle_list', array('uid' => $request->get('obituary'))));
            } catch (\Exception $e) {
                $this->flashError('Error while saving Data: '.$e->getMessage());
            }

        }

        return $this->render('AspetosAdminBundle:Layout:form.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'Candle',
            'icon'  => 'fa fa-fire'
        ));
    }

    /**
     * @param BookEntry $bookentry
     * @param Request   $request
     *
     * @Route("/delete/{id}")
     * @ParamConverter("bookentry", class="Model:BookEntry")
     * @Method({"GET", "DELETE"})
     * @return Response
     */
    public function deleteAction(BookEntry $bookentry, Request $request)
    {
        try {
            $this->getService()->getEm()->remove($bookentry);
            $this->getService()->flush();
            $this->flashSuccess('Data successfully removed');
        } catch (BookEntryNotFoundException $e) {
            $this->flashError('Object with this ID not found ('.$request->get('id').')');
        } catch (\Exception $e) {
            $this->flashError('Unexpected Error: '.$e->getMessage());
        }

        return $this->redirect($this->generateUrl('aspetos_legacy_bookentry_list'));

        return new Response();
    }

    /**
     * @param BookEntry $bookEntry
     * @param Request   $request
     *
     * @ParamConverter("bookEntry", class="Legacy:BookEntry")
     * @Method({"POST"})
     * @Route("/toggle/{id}")
     * @return JsonResponse
     */
    public function toogleWinnerAction(BookEntry $bookEntry, Request $request)
    {
        $state = $request->get('state');

        try {
            $state = ($state == 'true') ? 1 : 0;
            $bookEntry->setHide($state);
            $this->getService()->flush();

        } catch (\Exception $e) {
            return new JsonResponse(array('success' => false, 'message' => $e->getMessage()));
        }

        return new JsonResponse(array('success' => true, 'message' => ($state) ? 'Candle now hidden' : 'Candle now unhidden'));
    }

    /**
     * @param int $uid
     *
     * @Route("/list/{uid}")
     * @Template()
     *
     * @return array
     */
    public function listAction($uid)
    {
        $dataTable = $this->get('aspetos.legacy.grid.candle');
        $dataTable->uid = $uid;
        $dataTable->get();
        $bookId = $this->getService()->findBookForObituary($uid, 'candle');

        return array('bookId' => $bookId, 'uid' => $uid);
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
     * @param Request $request
     *
     * @Route("/grid/{bookId}/{uid}")
     * @return Response
     */
    public function gridAction(Request $request)
    {
        $datatable = $this->get('aspetos.legacy.grid.candle');
        $datatable->uid = $request->get('uid');
        $qb = $datatable->get()->getQueryBuilder()->getDoctrineQueryBuilder();
        $qb->where('x.bookId = :bookId')
           ->setParameter('bookId', $request->get('bookId'));

        return $datatable->execute();
    }

    /**
     * @return BookEntryService
     */
    protected function getService()
    {
        return $this->get('aspetos.service.legacy.bookentry');
    }
}
