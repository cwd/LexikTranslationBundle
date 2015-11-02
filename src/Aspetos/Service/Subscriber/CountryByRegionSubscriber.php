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
use Aspetos\Model\Entity\Company;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class AddressSetDistrictSubscriber
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.subscriber.company_country")
 * @DI\DoctrineListener(
 *     events = {"prePersist", "preUpdate"},
 *     connection = "default",
 *     lazy = true,
 *     priority = 100,
 * )
 */
class CountryByRegionSubscriber
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        if ($args->getObject() instanceof Company) {
            $this->setCountryByRegion($args->getObject());
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        if ($args->getObject() instanceof Address) {
            $this->setCountryByRegion($args->getObject());
        }
    }

    /**
     * @param Company $object
     */
    public function setCountryByRegion(Company $object)
    {
        $address = $object->getAddress();
        if ($address !== null) {
            $region = $address->getRegion();
            if ($region !== null) {
                $object->setCountry($region->getCountry());
            }
        }
    }
}
