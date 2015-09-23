<?php
/*
 * This file is part of AspetosAdminBundle
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\LegacyBundle\Menu;

use Aspetos\Bundle\AdminBundle\Event\ConfigureMenuEvent;

/**
 * Class MainMenuListener
 *
 * @package Aspetos\Bundle\LegacyBundle\Menu
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class MainMenuListener
{
    /**
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('Aspetos 1.0', array('uri' => '#'))
             ->setLinkAttribute('class', 'menu-header')
             ->setAttribute('icon', 'fa fa-puzzle-piece')
             ->setAttribute('divider_prepend', true);


        $menu->addChild('Obituary', array('route' => 'aspetos_legacy_obituary_list'))
            ->setAttribute('icon', 'fa fa-bookmark')
            ->setDisplayChildren(false);

        $menu->addChild('Mortician', array('route' => 'aspetos_legacy_mortician_list'))
            ->setAttribute('icon', 'fa fa-taxi')
            ->setDisplayChildren(false);

        $menu->addChild('Company', array('route' => 'aspetos_legacy_company_list'))
            ->setAttribute('icon', 'fa fa-building-o')
            ->setDisplayChildren(false);

        $menu->addChild('User', array('route' => 'aspetos_legacy_user_list'))
            ->setAttribute('icon', 'fa fa-user')
            ->setDisplayChildren(false)
            ->setAttribute('class', 'asdf');

        $menu->addChild('Statistic', array('route' => 'aspetos_legacy_statistic_index'))
            ->setAttribute('icon', 'fa fa-bar-chart-o')
            ->setDisplayChildren(false)
            ->setAttribute('class', 'asdf');
    }
}
