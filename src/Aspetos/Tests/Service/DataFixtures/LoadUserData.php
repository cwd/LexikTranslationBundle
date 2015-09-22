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
use Aspetos\Model\Entity\User;

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

        $group = new User();
        $group->setFirstname('Max')
            ->setLastname('Mustermann')
            ->setEmail('user@host')
            ->setPassword('asdf')
            ->setId(1)
            ->setEnabled(1)
            ->setCreatedAt(new \DateTime())
            ->addUserRole($this->getReference('role-super'));
        $manager->persist($group);
        $this->addReference('user1', $group);

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
