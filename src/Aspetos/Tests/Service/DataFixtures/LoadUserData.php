<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Tests\Service\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Aspetos\Model\Entity\Admin;

/**
 * Loads countries data
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load fixtures
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manager->clear();
        gc_collect_cycles(); // Could be useful if you have a lot of fixtures

        $group = new Admin();
        $group->setFirstname('Max')
            ->setLastname('Mustermann')
            ->setEmail('max.mustermann@dummy.local')
            ->setPassword('asdf')
            ->setId(1)
            ->setEnabled(1)
            ->setCreatedAt(new \DateTime())
            ->addUserRole($this->getReference('ROLE_SUPER_ADMIN'));

        $manager->persist($group);
        $this->addReference('admin', $group);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}
