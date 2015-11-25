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

use Aspetos\Model\Entity\BaseUser;
use Aspetos\Model\Entity\MorticianUser;
use Aspetos\Service\Event\UserEvent;
use Aspetos\Service\Listener\UserPasswordListener;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Aspetos\Model\Entity\Admin;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Loads countries data
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    /**
     * Load fixtures
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $manager->clear();
        gc_collect_cycles(); // Could be useful if you have a lot of fixtures

        $user = new BaseUser();
        $user->setFirstname('Max')
            ->setLastname('Mustermann')
            ->setEmail('max.mustermann@dummy.local')
            ->setPlainPassword('asdf')
            ->setId(1)
            ->setEnabled(1)
            ->setCreatedAt(new \DateTime())
            ->addGroup($this->getReference('ROLE_SUPER_ADMIN'))
        ;
        $manager->persist($user);

        $admin = new Admin();
        $admin->setUser($user);
        $manager->persist($admin);

        $this->container->get('fos_user.user_manager')->updateUser($user);

        $this->addReference('admin', $user);

        $morticanUser = new BaseUser();
        $morticanUser->setFirstname('Mortician')
             ->setLastname('Dummy')
             ->setEmail('mortican@dummy.local')
             ->setId(2)
             ->setEnabled(1)
             ->setCreatedAt(new \DateTime())
             ->setPlainPassword('asdf')
             ->addGroup($this->getReference('ROLE_MORTICIAN'))
             ->addPermission($this->getReference('permission-mortician.view'));

        $this->container->get('fos_user.user_manager')->updateUser($morticanUser);
        $manager->persist($morticanUser);

        $morticianU = new MorticianUser();
        $morticianU->setMortician($this->getReference('mortician'))
                   ->setUser($morticanUser);
        $manager->persist($morticianU);

        $this->addReference('user-mortician', $morticanUser);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 5; // the order in which fixtures will be loaded
    }
}
