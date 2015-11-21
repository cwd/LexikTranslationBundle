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

use Aspetos\Model\Entity\Mortician as Entity;
use Aspetos\Model\Repository\MorticianRepository as EntityRepository;
use Aspetos\Service\Exception\MorticianNotFoundException as NotFoundException;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;

/**
 * Class Aspetos Service Mortician
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @method Entity getNew()
 * @method Entity find($pid)
 * @method EntityRepository getRepository()
 * @method NotFoundException createNotFoundException($message = null, $code = null, $previous = null)
 *
 * @DI\Service("aspetos.service.mortician", parent="cwd.generic.service.generic")
 */
class MorticianService extends BaseService
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
            'modelName'                 => 'Model:Mortician',
            'notFoundExceptionClass'    => 'Aspetos\Service\Exception\MorticianNotFoundException',
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
     * @param Entity $mortician
     * @param int    $supplierId
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function addSupplierById(Entity $mortician, $supplierId)
    {
        $supplier = $this->getEm()->getReference('Model:Supplier', $supplierId);
        $mortician->addSupplier($supplier);
    }

    /**
     * @param Entity $mortician
     * @param int    $cemeteryId
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function addCemeteryById(Entity $mortician, $cemeteryId)
    {
        $cemetery = $this->getEm()->getReference('Model:Cemetery', $cemeteryId);
        $mortician->addCemetery($cemetery);
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
