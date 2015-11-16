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
use Aspetos\Model\Entity\District as Entity;
use Aspetos\Service\Exception\DistrictNotFoundException as NotFoundException;
use Monolog\Logger;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Aspetos Service District
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.district", parent="cwd.generic.service.generic")
 */
class DistrictService extends Generic
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
    public function __construct(EntityManager $entityManager, Logger $logger, TokenStorage $tokenStorage)
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
            $obj = parent::findById('Model:District', intval($pid));

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
     * @param string $country
     * @return mixed
     */
    public function findByCountry($country)
    {
        return $this->getEm()->getRepository('Model:District')->findByCountry($country);
    }
}
