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
 * Class Aspetos Service Statistic
 *
 * @package Aspetos\Service\Legacy
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.legacy.statistic", parent="cwd.generic.service.generic")
 */
class StatisticService extends Generic
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
     * @param string $type
     * @param string $country
     *
     * @return array
     */
    public function getData($type = 'quantityCandle', $country = 'at')
    {
        return $this->getEm()->getRepository('Legacy:StatisticAggUserMortician')->getStatistic($type, $country);
    }

    /**
     * Get Periods
     * @return mixed
     */
    public function getPeriod()
    {
        return $this->getEm()->getRepository('Legacy:StatisticAggUserMortician')->getPeriod();
    }

    /**
     * @param string $orderBy
     * @param string $orderDir
     * @param int    $offset
     * @param int    $count
     * @param null   $year
     * @param null   $month
     *
     * @return mixed
     */
    public function getTop($orderBy = 's.quantityDeadUser', $orderDir='DESC', $offset = 0, $count = 20, $year = null, $month = null)
    {
        return $this->getEm()->getRepository('Legacy:StatisticAggUserMortician')->getData($year, $month, $orderBy, $orderDir, $offset, $count);
    }

}
