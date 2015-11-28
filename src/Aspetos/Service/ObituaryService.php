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

use Aspetos\Model\Entity\Obituary as Entity;
use Aspetos\Model\Repository\ObituaryRepository as EntityRepository;
use Aspetos\Service\Exception\ObituaryNotFoundException as NotFoundException;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Aspetos Service Obituary
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @method Entity getNew()
 * @method Entity find($pid)
 * @method EntityRepository getRepository()
 * @method NotFoundException createNotFoundException($message = null, $code = null, $previous = null)
 *
 * @DI\Service("aspetos.service.obituary", parent="cwd.generic.service.generic")
 */
class ObituaryService extends BaseService
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @param EntityManager   $entityManager
     * @param LoggerInterface $logger
     * @param TokenStorage    $tokenStorage
     *
     * @DI\InjectParams({
     * })
     */
    public function __construct(EntityManager $entityManager, LoggerInterface $logger, TokenStorage $tokenStorage)
    {
        parent::__construct($entityManager, $logger);
        $this->tokenStorage  = $tokenStorage;
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
            'modelName'                 => 'Model:Obituary',
            'notFoundExceptionClass'    => 'Aspetos\Service\Exception\ObituaryNotFoundException',
        );
    }

    /**
     * @param int $uid
     *
     * @return Entity
     * @throws NotFoundException
     */
    public function findByUid($uid)
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
     * @param Entity $obituary
     * @param null   $state
     * @param null   $fromDate
     * @param null   $toDate
     * @return mixed
     */
    public function getCountCandles(Entity $obituary, $state = null, $fromDate = null, $toDate = null)
    {
        return $this->getEm()->getRepository('Model:Candle')->getCountByObituary($obituary, $state, $fromDate, $toDate);
    }

    /**
     * @param Entity $obituary
     * @param null   $state
     * @param null   $fromDate
     * @param null   $toDate
     * @return mixed
     */
    public function getCountCondolences(Entity $obituary, $state = null, $fromDate = null, $toDate = null)
    {
        return $this->getEm()->getRepository('Model:Condolence')->getCountByObituary($obituary, $state, $fromDate, $toDate);
    }

    /** 
     * @param array $search
     * @param array $exclude
     * @param int   $offset
     * @param int   $count
     * @return mixed
     */
    public function search($search = array(), $exclude = null, $offset = 0, $count = 20)
    {
        return $this->getEm()->getRepository('Model:Obituary')->search($search, $exclude, $offset, $count);
    }
}
