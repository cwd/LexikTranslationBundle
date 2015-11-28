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

use Aspetos\Model\Entity\Candle as Entity;
use Aspetos\Model\Entity\Obituary;
use Aspetos\Model\Repository\CandleRepository as EntityRepository;
use Aspetos\Service\BaseService;
use Aspetos\Service\Exception\CandleNotFoundException as NotFoundException;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;

/**
 * Class Aspetos Service Candle
 *
 * @package Aspetos\Service\Supplier
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @method Entity getNew()
 * @method Entity find($pid)
 * @method EntityRepository getRepository()
 * @method NotFoundException createNotFoundException($message = null, $code = null, $previous = null)
 *
 * @DI\Service("aspetos.service.obituary.candle", parent="cwd.generic.service.generic")
 */
class CandleService extends BaseService
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
            'modelName'                 => 'Model:Candle',
            'notFoundExceptionClass'    => 'Aspetos\Service\Exception\CandleNotFoundException',
        );
    }

    /**
     * @param int $uid
     *
     * @return Entity
     * @throws NotFoundException
     */
    public function findByOrigId($uid)
    {
        try {
            $obj = $this->findOneByFilter($this->getModelName(), array('origId' => $uid));

            if ($obj === null) {
                throw $this->createNotFoundException('Row with UID '.$uid.' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw $this->createNotFoundException($e->getMessage());
        }
    }

    /**
     * @param array $search
     * @param array $exclude
     * @param bool  $getInactive
     * @param int   $offset
     * @param int   $count
     * @return mixed
     */
    public function search($search = array(), $exclude = null, $getInactive = false, $offset = 0, $count = 20)
    {
        return $this->getEm()->getRepository('Model:Candle')->search($search, $exclude, $getInactive, $offset, $count);
    }
}
