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
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class Main Menu
 *
 * @package Aspetos\Bundle\AdminBundle\Menu
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class Main extends ContainerAware
{
    /**
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function mainMenu(FactoryInterface $factory, array $options = array())
    {
        $context    = $this->container->get('security.authorization_checker');
        $request    = $this->container->get('request');

        $admin      = $context->isGranted('ROLE_ADMIN');

        $menu = $factory->createItem('root');

        $menu->addChild('Dashboard', array('route' => 'aspetos_admin_dashboard_index'))
            ->setAttribute('icon', 'fa fa-home');

        if ($context->isGranted('ROLE_SHOPMANAGER')) {
        }

        if ($context->isGranted('ROLE_ADMIN')) {
            $menu->addChild('Users', array('route' => 'aspetos_admin_user_user_list'))
                ->setAttribute('icon', 'fa fa-user')
            ;
        }

        $this->container->get('event_dispatcher')->dispatch(
            ConfigureMenuEvent::CONFIGURE,
            new ConfigureMenuEvent($factory, $menu)
        );

        return $menu;
    }
}
