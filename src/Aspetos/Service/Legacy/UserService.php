<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Legacy;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Cwd\GenericBundle\Service\Generic;
use Aspetos\Bundle\LegacyBundle\Model\Entity\User as Entity;
use Aspetos\Service\Legacy\Exception\ObituaryNotFoundException as NotFoundException;
use Monolog\Logger;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Aspetos Service Obituary
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.legacy.user", parent="cwd.generic.service.generic")
 */
class UserService extends Generic
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @param EntityManager $legacyEntityManager
     * @param Logger        $logger
     * @param TokenStorage  $tokenStorage
     *
     * @DI\InjectParams({
     * })
     */
    public function __construct(EntityManager $legacyEntityManager, Logger $logger, TokenStorage $tokenStorage)
    {
        parent::__construct($legacyEntityManager, $logger);
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
            $obj = parent::findById('Legacy:User', intval($pid));

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
     * Get Staticdata
     * @param string $country
     * @param string $group
     *
     * @return array
     */
    public function getStatistic($country = 'at', $group = 'month')
    {
        return $this->getEm()->getRepository('Legacy:User')->getStatistic('default', $country, $group);
    }

    /**
     * @return Entity
     */
    public function getNew()
    {
        return new Entity();
    }
}
