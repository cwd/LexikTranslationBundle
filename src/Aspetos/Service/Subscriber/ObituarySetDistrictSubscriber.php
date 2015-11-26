<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Subscriber;

use Aspetos\Model\Entity\Address;
use Aspetos\Model\Entity\Obituary;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class AddressSetDistrictSubscriber
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.subscriber.obituary_district")
 * @DI\DoctrineListener(
 *     events = {"prePersist", "preUpdate"},
 *     connection = "default",
 *     lazy = true,
 *     priority = 200,
 * )
 */
class ObituarySetDistrictSubscriber
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        if ($args->getObject() instanceof Obituary) {
            $this->setRegionByDistrict($args->getObject());
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        if ($args->getObject() instanceof Obituary) {
            $this->setRegionByDistrict($args->getObject());
        }
    }

    /**
     * @param Obituary $obituary
     */
    public function setRegionByDistrict(Obituary $obituary)
    {
        if ($obituary->getCemetery() == null) {
            return;
        }

        $district = $obituary->getCemetery()->getAddress()->getDistrict();
        if ($district !== null) {
            $obituary->setCountry($obituary->getCemetery()->getAddress()->getCountry())
                     ->setDistrict($obituary->getCemetery()->getAddress()->getDistrict());
        }
    }
}
