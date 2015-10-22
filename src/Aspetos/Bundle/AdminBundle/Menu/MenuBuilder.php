<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Menu;

use Aspetos\Bundle\AdminBundle\Event\ConfigureMenuEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Main Menu
 *
 * @package Aspetos\Bundle\AdminBundle\Menu
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $securityContext;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param FactoryInterface              $factory
     * @param AuthorizationCheckerInterface $securityContext
     * @param EventDispatcherInterface      $dispatcher
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $securityContext, EventDispatcherInterface $dispatcher)
    {
        $this->factory = $factory;
        $this->securityContext = $securityContext;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param array $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu(array $options = array())
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Dashboard', array('route' => 'aspetos_admin_dashboard_index'))
            ->setAttribute('icon', 'fa fa-home');

        if ($this->securityContext->isGranted('ROLE_SHOPMANAGER')) {
        }

        if ($this->securityContext->isGranted('ROLE_ADMIN')) {
            $menu->addChild('Users', array('route' => 'aspetos_admin_user_user_list'))
                ->setAttribute('icon', 'fa fa-user');

            $menu->addChild('Cemeteries', array('route' => 'aspetos_admin_cemetery_list'))
                ->setAttribute('icon', 'asp asp-grave');

            $menu->addChild('Morticians', array('route' => 'aspetos_admin_mortician_mortician_list'))
                ->setAttribute('icon', 'fa fa-battery-4 fa-rotate-270');
        }

        if ($this->securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $menu->addChild('Permissions', array('route' => 'aspetos_admin_permission_list'))
                ->setAttribute('icon', 'fa fa-lock');
        }

        $this->dispatcher->dispatch(
            ConfigureMenuEvent::CONFIGURE,
            new ConfigureMenuEvent($this->factory, $menu)
        );

        return $menu;
    }
}
