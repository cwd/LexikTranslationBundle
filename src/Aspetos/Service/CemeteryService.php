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

use Aspetos\Model\Entity\Cemetery as Entity;
use Aspetos\Model\Repository\CemeteryRepository as EntityRepository;
use Aspetos\Service\Exception\CemeteryNotFoundException as NotFoundException;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Aspetos Service Cemetery
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @method Entity getNew()
 * @method Entity find($pid)
 * @method EntityRepository getRepository()
 * @method NotFoundException createNotFoundException($message = null, $code = null, $previous = null)
 *
 * @DI\Service("aspetos.service.cemetery", parent="cwd.generic.service.generic")
 */
class CemeteryService extends BaseService
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
            'modelName'                 => 'Model:Cemetery',
            'notFoundExceptionClass'    => 'Aspetos\Service\Exception\CemeteryNotFoundException',
        );
    }

    /**
     * @param string $query
     *
     * @return array
     */
    public function findAllActiveAsArray($query = null)
    {
        $cemeteries = $this->getRepository()->findAllActiveAsArray($query);
        $result = array();

        foreach ($cemeteries as $cemetery) {
            $result[$cemetery['address']['region']['name']][] = $cemetery;
        }

        return $result;
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
        return $this->getRepository()->search($search, $exclude, $offset, $count);
    }
}
