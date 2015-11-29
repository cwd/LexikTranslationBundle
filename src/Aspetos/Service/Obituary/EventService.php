<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Obituary;

use Aspetos\Model\Entity\Event as Entity;
use Aspetos\Model\Entity\Obituary;
use Aspetos\Model\Repository\EventRepository as EntityRepository;
use Aspetos\Service\BaseService;
use Aspetos\Service\Exception\EventNotFoundException as NotFoundException;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;

/**
 * Class Aspetos Service Event
 *
 * @package Aspetos\Service\Supplier
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @method Entity getNew()
 * @method Entity find($pid)
 * @method EntityRepository getRepository()
 * @method NotFoundException createNotFoundException($message = null, $code = null, $previous = null)
 *
 * @DI\Service("aspetos.service.obituary.event", parent="cwd.generic.service.generic")
 */
class EventService extends BaseService
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
            'modelName'                 => 'Model:Event',
            'notFoundExceptionClass'    => 'Aspetos\Service\Exception\EventNotFoundException',
        );
    }

    /**
     * @param array $search
     * @param array $exclude
     * @param bool  $getFutureEvents
     * @param int   $offset
     * @param int   $count
     * @param array $orderBy
     * @return mixed
     */
    public function search($search = array(), $exclude = null, $getFutureEvents = true, $offset = 0, $count = 20, $orderBy = null)
    {
        return $this->getEm()->getRepository('Model:ObituaryEvent')->search($search, $exclude, $getFutureEvents, $offset, $count, $orderBy);
    }
}
