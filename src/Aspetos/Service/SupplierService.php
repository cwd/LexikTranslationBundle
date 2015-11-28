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

use Aspetos\Model\Entity\Supplier as Entity;
use Aspetos\Model\Repository\SupplierRepository as EntityRepository;
use Aspetos\Service\Exception\SupplierNotFoundException as NotFoundException;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;

/**
 * Class Aspetos Service Supplier
 *
 * @package Aspetos\Service\Supplier
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @method Entity getNew()
 * @method Entity find($pid)
 * @method EntityRepository getRepository()
 * @method NotFoundException createNotFoundException($message = null, $code = null, $previous = null)
 *
 * @DI\Service("aspetos.service.supplier", parent="cwd.generic.service.generic")
 */
class SupplierService extends BaseService
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
            'modelName'                 => 'Model:Supplier',
            'notFoundExceptionClass'    => 'Aspetos\Service\Exception\SupplierNotFoundException',
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
     * @param string $query
     *
     * @return array
     */
    public function findAllActiveAsArray($query = null)
    {
        $suppliers = $this->getRepository()->findAllActiveAsArray($query);
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
     * @param array $search
     * @param array $exclude
     * @param int   $offset
     * @param int   $count
     * @param array $orderBy
     * @return mixed
     */
    public function search($search = array(), $exclude = null, $offset = 0, $count = 20, $orderBy = null)
    {
        return $this->getRepository()->search($search, $exclude, $offset, $count, $orderBy);
    }
}
