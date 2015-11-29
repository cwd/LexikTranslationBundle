<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\FrontendBundle\Menu;

use Aspetos\Bundle\FrontendBundle\Event\ConfigureMenuEvent;
use Cwd\WordpressApiBundle\Service\WordpressApi;
use Gedmo\Sluggable\Util\Urlizer;
use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class MenuBuilder
 *
 * @package Aspetos\Bundle\FrontendBundle\Menu
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
     * @var Request
     */
    protected $request;

    /**
     * @var WordpressApi
     */
    protected $wordpressApi;

    /**
     * @var int
     */
    protected $wpMenuNewsId;

    /**
     * @var int
     */
    protected $wpMenuFooterId;

    /**
     * @param FactoryInterface              $factory
     * @param AuthorizationCheckerInterface $securityContext
     * @param EventDispatcherInterface      $dispatcher
     * @param RequestStack                  $request
     * @param WordpressApi                  $wordpressApi
     * @param int                           $wpMenuNewsId
     * @param int                           $wpMenuFooterId
     */
    public function __construct(
        FactoryInterface $factory,
        AuthorizationCheckerInterface $securityContext,
        EventDispatcherInterface $dispatcher,
        RequestStack $request,
        WordpressApi $wordpressApi,
        $wpMenuNewsId,
        $wpMenuFooterId
    )
    {
        $this->factory = $factory;
        $this->securityContext = $securityContext;
        $this->dispatcher = $dispatcher;
        $this->request = $request;
        $this->wordpressApi = $wordpressApi;
        $this->wpMenuNewsId = $wpMenuNewsId;
        $this->wpMenuFooterId = $wpMenuFooterId;
    }

    /**
     * @param array $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu(array $options = array())
    {
        $menu = $this->factory->createItem('root');

        $shopItem = $menu
            ->addChild('Shop', array('route' => 'aspetos_shop_index'))
            ->setDisplayChildren(false);

        $obituaryItem = $menu->addChild('Obituaries', array('route' => 'aspetos_frontend_obituary_list'));
        $obituaryItem->addChild('Prominents', array('route' => 'aspetos_frontend_obituary_prominents'));
        $obituaryItem->addChild('Children', array('route' => 'aspetos_frontend_obituary_children'));
        $obituaryItem->addChild('Anniversaries', array('route' => 'aspetos_frontend_obituary_anniversaries'));

        $menu->addChild('Forum', array('route' => 'aspetos_frontend_default_index'));

        $wpMenu = $this->wordpressApi->menu($this->wpMenuNewsId);
        if (!empty($wpMenu)) {
            $newsItem = $menu->addChild('News', array('route' => 'aspetos_frontend_default_index'));
            $this->addWpMenuItems($newsItem, $wpMenu['items'], 'aspetos_frontend_wordpress_category');
        }

        $catalogItem = $menu->addChild('Catalog', array('route' => 'aspetos_frontend_default_index'));
        $catalogItem->addChild('Morticians', array('route' => 'aspetos_frontend_catalog_morticians'));
        $catalogItem->addChild('Suppliers', array('route' => 'aspetos_frontend_catalog_suppliers'));
        $catalogItem->addChild('Cemeteries', array('route' => 'aspetos_frontend_catalog_cemeteries'));

        $menu->addChild('Funeral provision', array('route' => 'aspetos_frontend_default_index'));

        $this->dispatcher->dispatch(
            ConfigureMenuEvent::CONFIGURE,
            new ConfigureMenuEvent($this->factory, $menu)
        );

        return $menu;
    }

    /**
     * @param array $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createFooterMenu(array $options = array())
    {
        $menu = $this->factory->createItem('root');

        $wpMenu = $this->wordpressApi->menu($this->wpMenuFooterId);
        if (!empty($wpMenu)) {
            $this->addWpMenuItems($menu, $wpMenu['items'], 'aspetos_frontend_wordpress_post', 'fa fa-angle-right');
        }

        return $menu;
    }

    /**
     * @param MenuItem $menu
     * @param array    $items
     * @param string   $routeName
     * @param string   $icon
     */
    protected function addWpMenuItems(MenuItem $menu, Array $items, $routeName, $icon = null)
    {
        foreach ($items as $item) {
            $menuItem = $menu->addChild(
                $item['title'],
                array(
                    'route'         => $routeName,
                    'routeParameters'   => array(
                        'slug'  => Urlizer::urlize($item['title']),
                        'id'    => $item['object_id']
                    )
                )
            );
            if ($icon !== null) {
                $menuItem->setAttribute('icon', $icon);
            }
            $this->addWpMenuItems($menuItem, $item['children'], $routeName, $icon);
        }
    }
}
