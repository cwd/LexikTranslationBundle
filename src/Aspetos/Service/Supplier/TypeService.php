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

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Cwd\GenericBundle\Service\Generic;
use Aspetos\Model\Entity\SupplierType as Entity;
use Aspetos\Service\Exception\SupplierTypeNotFoundException as NotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Aspetos Service SupplierType
 *
 * @package Aspetos\Service\Supplier
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.supplier.type", parent="cwd.generic.service.generic")
 */
class TypeService extends Generic
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
     * Find Object by ID
     *
     * @param int $pid
     *
     * @return Entity
     * @throws NotFoundException
     */
    public function find($pid)
    {
        try {
            $obj = parent::findById('Model:SupplierType', intval($pid));

            if ($obj === null) {
                $this->getLogger()->info('Row with ID {id} not found', array('id' => $pid));
                throw new NotFoundException('Row with ID ' . $pid . ' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw new NotFoundException();
        }
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
            $obj = $this->findOneByFilter('Model:SupplierType', array('origId' => $id));

            if ($obj === null) {
                throw new NotFoundException('Row with ID '.$id.' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw new NotFoundException($e->getMessage());
        }
    }

    /**
     * @return Entity
     */
    public function getNew()
    {
        return new Entity();
    }

    /**
     * @param int $amount
     * @param int $offset
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function findAll($amount = 10000, $offset = 0)
    {
        return $this->findAllByModel('Model:SupplierType', array(), array(), $amount, $offset);
    }
}
