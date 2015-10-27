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

use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class Aspetos Service Company
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.legacy.company", parent="aspetos.service.legacy.user")
 */
class CompanyService extends UserService
{
    /**
     * Get Staticdata
     *
     * @param string $country
     * @param string $group
     *
     * @return array
     */
    public function getStatistic($country = 'at', $group = 'month')
    {
        return $this->getEm()->getRepository('Legacy:User')->getStatistic('company', $country, $group);
    }


    /**
     * @param int $amount
     * @param int $offset
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function findAll($amount = 10000, $offset = 0)
    {
        return $this->findAllByModel('Legacy:User', array('userCategory' => 'company'), array(), $amount, $offset);
    }
}
