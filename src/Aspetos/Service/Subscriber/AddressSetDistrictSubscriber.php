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
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class AddressSetDistrictSubscriber
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.subscriber.address_district")
 * @DI\DoctrineListener(
 *     events = {"prePersist", "preUpdate"},
 *     connection = "default",
 *     lazy = true,
 *     priority = 0,
 * )
 */
class AddressSetDistrictSubscriber
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        if ($args->getObject() instanceof Address) {
            $this->setRegionByDistrict($args->getObject());
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        if ($args->getObject() instanceof Address) {
            $this->setRegionByDistrict($args->getObject());
        }
    }

    /**
     * @param Address $address
     */
    public function setRegionByDistrict(Address $address)
    {
        $district = $address->getDistrict();
        if ($district !== null) {
            $address->setRegion($district->getRegion());
        }
    }
}
