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

use Aspetos\Model\Entity\Condolence as Entity;
use Aspetos\Model\Repository\CondolenceRepository as EntityRepository;
use Aspetos\Service\Exception\CondolenceNotFoundException as NotFoundException;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;

/**
 * Class Aspetos Service Condolence
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @method Entity getNew()
 * @method Entity find($pid)
 * @method EntityRepository getRepository()
 * @method NotFoundException createNotFoundException($message = null, $code = null, $previous = null)
 *
 * @DI\Service("aspetos.service.condolence", parent="cwd.generic.service.generic")
 */
class CondolenceService extends BaseService
{
    /**
     * @param EntityManager   $entityManager
     * @param LoggerInterface $logger
     *
     * @DI\InjectParams({
     * })
     */
    public function __construct(EntityManager $entityManager, LoggerInterface $logger)
    {
        parent::__construct($entityManager, $logger);
    }

    /**
     * Set raw option values right before validation. This can be used to chain
     * options in inheritance setups.
     *
     * @return array
     */
    protected function setServiceOptions()
    {
        return array(
            'modelName'                 => 'Model:Condolence',
            'notFoundExceptionClass'    => 'Aspetos\Service\Exception\CondolenceNotFoundException',
        );
    }
}
