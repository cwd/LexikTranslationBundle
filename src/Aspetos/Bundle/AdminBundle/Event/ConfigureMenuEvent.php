<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Event;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ConfigureMenuEvent
 *
 * @package Aspetos\Bundle\AdminBundle\Event
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class ConfigureMenuEvent extends Event
{
    const CONFIGURE = 'aspetos_admin_bundle.menu_configure';

    private $factory;
    private $menu;

    /**
     * @param FactoryInterface $factory
     * @param ItemInterface    $menu
     */
    public function __construct(FactoryInterface $factory, ItemInterface $menu)
    {
        $this->factory = $factory;
        $this->menu = $menu;
    }

    /**
     * @return FactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return ItemInterface
     */
    public function getMenu()
    {
        return $this->menu;
    }
}
