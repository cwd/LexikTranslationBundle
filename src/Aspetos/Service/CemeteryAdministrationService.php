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

use Aspetos\Service\Exception\CemeteryAdministrationNotFoundException;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Cwd\GenericBundle\Service\Generic;
use Aspetos\Model\Entity\CemeteryAdministration as Entity;
use Aspetos\Service\Exception\CemeteryAdministrationNotFoundException as NotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Aspetos Service CemeteryAdministration
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.cemetery.administration", parent="cwd.generic.service.generic")
 */
class CemeteryAdministrationService extends Generic
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @param EntityManager $entityManager
     * @param Logger        $logger
     * @param TokenStorage  $tokenStorage
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
            $obj = parent::findById('Model:CemeteryAdministration', intval($pid));

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
     * @return Entity
     */
    public function getNew()
    {
        return new Entity();
    }

    /**
     * @param string $name
     * @param string $street
     * @param string $city
     * @param int    $zipcode
     * @return Entity
     */
    public function findOneByNameAndAddress($name, $street, $city, $zipcode)
    {
        try {
            $filter = array(
                'name'      => $name,
                'street'    => $street,
                'city'      => $city,
                'zipcode'   => $zipcode
            );
            $obj =  $this->findOneByFilter('Model:CemeteryAdministration', $filter);

            if ($obj === null) {
                $this->getLogger()->info('Row with name {name} not found', array('name' => $name));
                throw new CemeteryAdministrationNotFoundException('Row with name ' . $name . ' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw new CemeteryAdministrationNotFoundException();
        }
    }
}