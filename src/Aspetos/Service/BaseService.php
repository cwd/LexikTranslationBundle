<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service;

use Cwd\GenericBundle\Options\ValidatedOptionsTrait;
use Cwd\GenericBundle\Service\Generic;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Base Service class to ease creation of model-specific service classes.
 * If this code proves useful, we should consider moving it into the Generic service.
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
abstract class BaseService extends Generic
{
    use ValidatedOptionsTrait;

    /**
     * Set defaults required for the service definition.
     * @see BaseService::configureOptions()
     *
     * @return array
     */
    abstract protected function setServiceOptions();

    /**
     * Set default options, set required options - whatever is needed.
     * This will be called during first access to any of the object-related methods.
     *
     * @param OptionsResolverInterface $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        ));

        $resolver->setRequired(array(
            'modelName',
            'notFoundExceptionClass',
        ));
    }

    /**
     * Set raw option values right before validation. This can be used to chain
     * options in inheritance setups.
     *
     * @return array
     */
    protected function setOptions()
    {
        return $this->setServiceOptions();
    }

    /**
     * Return the Model to use inside this service - either by FQCN or by Doctrine alias.
     *
     * @return string
     */
    public function getModelName()
    {
        return $this->getOption('modelName');
    }

    /**
     * Return the NotFoundException class to use inside this service.
     *
     * @return string
     */
    public function getNotFoundExceptionClass()
    {
        return $this->getOption('notFoundExceptionClass');
    }

    /**
     * Create an exception instance using the class defined in $this->getNotFoundExceptionClass().
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     *
     * @return \Exception
     */
    public function createNotFoundException($message = null, $code = null, $previous = null)
    {
        $class = $this->getNotFoundExceptionClass();

        return new $class($message, $code, $previous);
    }

    /**
     * Shortcut to get entity repository for this service's model.
     *
     * @return Cwd\GenericBundle\Doctrine\EntityRepository
     */
    public function getRepository()
    {
        return $this->getEm()->getRepository($this->getModelName());
    }

    /**
     * Find Object by ID
     *
     * @param int $pid
     *
     * @return mixed
     * @throws \Exception
     */
    public function find($pid)
    {
        return $this->findOneByIdForModel($pid, $this->getModelName(), $this->getNotFoundExceptionClass());
    }

    /**
     * @return mixed
     */
    public function getNew()
    {
        $class = $this->getEm()->getClassMetadata($this->getModelName())->getName();

        return new $class;
    }
}
