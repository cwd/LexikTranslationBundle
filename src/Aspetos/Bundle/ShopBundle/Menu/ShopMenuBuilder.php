<?php
/*
 * This file is part of Aspetos
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\ShopBundle\Menu;

use Aspetos\Bundle\FrontendBundle\Event\ConfigureMenuEvent;
use JMS\DiExtraBundle\Annotation as DI;
use Knp\Menu\ItemInterface;
use Knp\Menu\FactoryInterface;
use Aspetos\Service\Product\CategoryService;

/**
 * Menu builder to create shop-related menus.
 * This service also registers as listener to append shop related items
 * to the frontend main menu.
 *
 * @package Aspetos\Bundle\ShopBundle\Menu
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.shop.menu_builder")
 * @DI\Tag("kernel.event_listener", attributes={
 *      "event": "aspetos_frontend_bundle.menu_configure",
 *      "method": "onFrontendMenuConfigure"
 * })
 * @DI\Tag("knp_menu.menu_builder", attributes={
 *      "alias": "shop_category_sidebar",
 *      "method": "createCategoryMenu"
 * })
 */
class ShopMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * @DI\InjectParams({
     *      "factory" = @DI\Inject("knp_menu.factory"),
     *      "categoryService" = @DI\Inject("aspetos.service.product.category")
     * })
     *
     * @param FactoryInterface $factory
     * @param CategoryService  $categoryService
     */
    public function __construct(
        FactoryInterface $factory,
        CategoryService $categoryService
    )
    {
        $this->factory = $factory;
        $this->categoryService = $categoryService;
    }

    /**
     * Builder method to create the category related menu items.
     *
     * @param array $options
     *
     * @return ItemInterface
     */
    public function createCategoryMenu(array $options = array())
    {
        $menu = $this->factory->createItem('root');

        $tree = $this->categoryService->getTreeAsArray();
        if (count($tree)) {
            // we do not want the "products" root node, so we skip to the children
            foreach ($tree[0]['__children'] as $childData) {
                $this->buildCategoryTree($menu, $childData);
            }
        }

        return $menu;
    }

    /**
     * Recursively build menu tree from array category data provided by CategoryService->getTreeAsArray().
     *
     * @param ItemInterface $parent
     * @param array $itemData
     */
    protected function buildCategoryTree(ItemInterface $parent, array $itemData)
    {
        $child = $parent->addChild($itemData['name'], array(
            'route' => 'aspetos_shop_category',
            'routeParameters' => array(
                'slug' => $itemData['slug'],
            ),
        ));

        foreach ($itemData['__children'] as $childData) {
            $this->buildCategoryTree($child, $childData);
        }
    }

    /**
     * This is the event listener for the frontend main menu configuration.
     * It is used to append the category and other shop items to the main shop menu item.
     *
     * @param ConfigureMenuEvent $event
     */
    public function onFrontendMenuConfigure(ConfigureMenuEvent $event)
    {
        $item = $this->findShopItem($event->getMenu());
        $item->addChild($this->createCategoryMenu());
    }

    /**
     * Find shop menu item inside main menu. This is done by checking the "route" extra.
     *
     * @param ItemInterface $menu
     *
     * @return ItemInterface
     */
    protected function findShopItem(ItemInterface $menu)
    {
        foreach ($menu->getChildren() as $item) {
            foreach ($item->getExtra('routes', array()) as $routeExtra) {
                if ('aspetos_shop_index' === $routeExtra['route']) {
                    return $item;
                }
            }
        }

        throw new \RuntimeException('Cannot find shop menu item');
    }
}
