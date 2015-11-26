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

use Aspetos\Service\BaseService;
use Aspetos\Service\Exception\NotFoundException;
use Cwd\GenericBundle\Controller\GenericController as CwdController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'checkModelClass'   => null,
            'redirectRoute'     => 'aspetos_admin_dashboard_index',
            'redirectParameter' => array(),
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
                    $this->getService()->persist($crudObject);
                }

                $this->getService()->flush();

                $this->flashSuccess($this->getOption('successMessage'));

                return $this->redirect($this->generateUrl($this->getOption('redirectRoute'), $this->getOption('redirectParameter')));
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
     * @return BaseService
     */
    protected function getService()
    {
        return $this->get($this->getOption('entityService'));
    }

    /**
     * Get new entity provided by the service.
     *
     * @return mixed
     */
    protected function getNewEntity()
    {
        return $this->getService()->getNew();
    }

    /**
     * @return Grid
     */
    protected function getGrid()
    {
        return $this->get($this->getOption('gridService'));
    }
}
