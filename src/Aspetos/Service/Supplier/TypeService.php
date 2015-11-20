<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Supplier;

use Aspetos\Model\Entity\SupplierType as Entity;
use Aspetos\Model\Repository\SupplierTypeRepository as EntityRepository;
use Aspetos\Service\BaseService;
use Aspetos\Service\Exception\SupplierTypeNotFoundException as NotFoundException;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;

/**
 * Class Aspetos Service SupplierType
 *
 * @package Aspetos\Service\Supplier
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @method Entity getNew()
 * @method Entity find($pid)
 * @method EntityRepository getRepository()
 * @method NotFoundException createNotFoundException($message = null, $code = null, $previous = null)
 *
 * @DI\Service("aspetos.service.supplier.type", parent="cwd.generic.service.generic")
 */
class TypeService extends BaseService
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
            'modelName'                 => 'Model:SupplierType',
            'notFoundExceptionClass'    => 'Aspetos\Service\Exception\SupplierTypeNotFoundException',
        );
    }

    /**
     * @param int $id
     *
     * @return Entity
     * @throws NotFoundException
     */
    public function findByOrigid($id)
    {
        try {
            $obj = $this->findOneByFilter($this->getModelName(), array('origId' => $id));

            if ($obj === null) {
                throw $this->createNotFoundException('Row with ID '.$id.' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw $this->createNotFoundException($e->getMessage());
        }
    }

    /**
     * @param int $amount
     * @param int $offset
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function findAll($amount = 10000, $offset = 0)
    {
        return $this->findAllByModel($this->getModelName(), array(), array(), $amount, $offset);
    }
}
