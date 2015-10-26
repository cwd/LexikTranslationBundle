<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Controller;

use Aspetos\Service\Exception\NotFoundException;
use Cwd\GenericBundle\Controller\GenericController as CwdController;
use Cwd\GenericBundle\Grid\Grid;
use Cwd\GenericBundle\Service\Generic;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class BaseController
 *
 * @package Aspetos\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
abstract class BaseController extends CwdController
{
    /**
     * Set default options, set required options - whatever is needed.
     * This will be called during first access to any of the object-related methods.
     *
     * @param OptionsResolverInterface $resolver
     */
    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'checkModelClass'   => null,
            'redirectRoute'     => 'aspetos_admin_dashboard_index',
            'successMessage'    => 'Data successfully saved',
            'formTemplate'      => 'AspetosAdminBundle:Layout:form.html.twig',
            'title'             => 'Admin',
        ));

        $resolver->setRequired(array(
            'entityService',
            'entityFormType',
            'gridService',
            'icon',
        ));
    }

    /**
     * @param misc    $crudObject
     * @param Request $request
     *
     * @Method({"GET", "DELETE"})
     * @return RedirectResponse
     */
    protected function deleteHandler($crudObject, Request $request)
    {
        $this->checkModelClass($crudObject);
        try {
            $this->getService()->remove($crudObject);
            $this->flashSuccess('Data successfully removed');
        } catch (NotFoundException $e) {
            $this->flashError('Object with this ID not found ('.$request->get('id').')');
        } catch (\Exception $e) {
            $this->flashError('Unexpected Error: '.$e->getMessage());
        }

        $redirectRoute = $this->getOption('redirectRoute');
        if ($redirectRoute !== null) {
            return $this->redirect($this->generateUrl($redirectRoute));
        }
    }

    /**
     * @param misc    $crudObject
     * @param Request $request
     * @param bool    $persist
     * @param array   $overrideOptions Override any class-level options
     *
     * @return RedirectResponse|Response
     */
    protected function formHandler($crudObject, Request $request, $persist = false)
    {
        $this->checkModelClass($crudObject);
        $form = $this->createForm($this->getOption('entityFormType'), $crudObject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($persist) {
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

    /**
     * Check if the given CRUD object matches the optionally configured "checkModelClass" option.
     *
     * @throws \InvalidArgumentException
     *
     * @param string $crudObject
     */
    protected function checkModelClass($crudObject)
    {
        $modelClass = $this->getOptionOrDefault('checkModelClass');
        if (null !== $modelClass && !$crudObject instanceof $modelClass) {
            throw new \InvalidArgumentException('Expected CRUD model class '.$modelClass.' but got '.get_class($crudObject));
        }
    }

    /**
     * @return Generic
     */
    protected function getService()
    {
        return $this->get($this->getOption('entityService'));
    }

    /**
     * @return Grid
     */
    protected function getGrid()
    {
        return $this->get($this->getOption('gridService'));
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

        return array('icon' => $this->getOption('icon'));
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
}
