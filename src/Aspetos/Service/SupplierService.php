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

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Cwd\GenericBundle\Service\Generic;
use Aspetos\Model\Entity\Supplier as Entity;
use Aspetos\Service\Exception\SupplierNotFoundException as NotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Aspetos Service Supplier
 *
 * @package Aspetos\Service\Supplier
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.supplier", parent="cwd.generic.service.generic")
 */
class SupplierService extends Generic
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
            $obj = parent::findById('Model:Supplier', intval($pid));

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
     * @param int $uid
     *
     * @return Entity
     * @throws NotFoundException
     */
    public function findByUid($uid)
    {
        try {
            $obj = $this->findOneByFilter('Model:Supplier', array('origId' => $uid));

            if ($obj === null) {
                throw new NotFoundException('Row with UID '.$uid.' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw new NotFoundException($e->getMessage());
        }
    }

    /**
     * @param string $query
     *
     * @return array
     */
    public function findAllActiveAsArray($query = null)
    {
        $suppliers = $this->getEm()->getRepository('Model:Supplier')->findAllActiveAsArray($query);
        $result = array();

        foreach ($suppliers as $supplier) {
            if (isset($supplier['supplierTypes'][0])) {
                $result[$supplier['supplierTypes'][0]['name']][] = $supplier;
            } else {
                $result['Andere'][] = $supplier;
            }
        }

        return $result;
    }

    /**
     * @return Entity
     */
    public function getNew()
    {
        return new Entity();
    }
}
