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

use Aspetos\Service\Exception\SupplierNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use JMS\DiExtraBundle\Annotation as DI;
use Cwd\GenericBundle\Service\Generic;
use Aspetos\Model\Entity\Mortician as Entity;
use Aspetos\Service\Exception\MorticianNotFoundException as NotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Aspetos Service Mortician
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.mortician", parent="cwd.generic.service.generic")
 */
class MorticianService extends Generic
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
            $obj = parent::findById('Model:Mortician', intval($pid));

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
            $obj = $this->findOneByFilter('Model:Mortician', array('origId' => $uid));

            if ($obj === null) {
                throw new NotFoundException('Row with UID '.$uid.' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw new NotFoundException($e->getMessage());
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
     * @return Entity
     */
    public function getNew()
    {
        return new Entity();
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
        return $this->getEm()->getRepository('Model:Mortician')->search($search, $exclude, $offset, $count);
    }
}
