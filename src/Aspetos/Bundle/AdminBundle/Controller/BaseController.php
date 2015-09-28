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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Cwd\GenericBundle\Service\Generic;
use Aspetos\Service\Exception\InvalidOptionException;
use Cwd\GenericBundle\Grid\Grid;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseController
 *
 * @package Aspetos\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
abstract class BaseController extends CwdController
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var array
     */
    protected $validatedOptions;

    /**
     * Get the given option value, validated by the OptionsResolver.
     *
     * @param string $name
     *
     * @return mixed
     */
    protected function getOption($name)
    {
        $this->validateOptions();

        if (!array_key_exists($name, $this->validatedOptions)) {
            throw new InvalidOptionException('Unknown option: '.$name);
        }

        return $this->validatedOptions[$name];
    }

    protected function validateOptions()
    {
        if (null === $this->validatedOptions) {
            $resolver = new OptionsResolver();
            $this->configureOptions($resolver);

            $this->validatedOptions = $resolver->resolve($this->options);
        }
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'redirectRoute' => 'aspetos_admin_dashboard_index',
            'successMessage' => 'Data successfully saved',
            'formTemplate' => 'AspetosAdminBundle:Layout:form.html.twig',
            'title' => 'Admin',
        ));

        $resolver->setRequired(array(
            'entityService',
            'entityFormType',
            'gridService',
            'icon',
        ));
    }

    /**
     * @param misc    $object
     * @param Request $request
     *
     * @Method({"GET", "DELETE"})
     * @return RedirectResponse
     */
    protected function deleteHandler($object, Request $request)
    {
        try {
            $this->getService()->remove($object);
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
     * @param misc    $object
     * @param Request $request
     * @param bool    $persist
     * @param array   $overrideOptions Override any class-level options
     *
     * @return RedirectResponse|Response
     */
    protected function formHandler($object, Request $request, $persist = false)
    {
        $form = $this->createForm($this->getOption('entityFormType'), $object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($persist) {
                    $this->getService()->create($object);
                } else {
                    $this->getService()->edit($object);
                }

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
}
