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
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
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
     * @var Request
     */
    protected $request;

    /**
     * @param FactoryInterface              $factory
     * @param AuthorizationCheckerInterface $securityContext
     * @param EventDispatcherInterface      $dispatcher
     * @param RequestStack                  $request
     */
    public function __construct(
        FactoryInterface $factory,
        AuthorizationCheckerInterface $securityContext,
        EventDispatcherInterface $dispatcher,
        RequestStack $request
    )
    {
        $this->factory = $factory;
        $this->securityContext = $securityContext;
        $this->dispatcher = $dispatcher;
        $this->request = $request->getCurrentRequest();
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

        if ($this->securityContext->isGranted('ROLE_MORTICIAN')) {
            $obituary = $menu->addChild('Obituaries', array('route' => 'aspetos_admin_obituary_list'))
                ->setAttribute('icon', 'fa fa-bookmark')
                ->setDisplayChildren(false);
            $obituary->addChild('Create', array('route' => 'aspetos_admin_obituary_create'));
            $obituary->addChild('Edit', array('route' => 'aspetos_admin_obituary_edit', 'routeParameters' => array('id' => $this->request->get('id', 0))));
            //$obituary->addChild('Detail', array('route' => 'aspetos_admin_obituary_detail', 'routeParameters' => array('id' => $this->request->get('id', 0))));

            $candle = $menu->addChild('Candles', array('route' => 'aspetos_admin_candle_list'))
                ->setAttribute('icon', 'fa fa-fire')
                ->setDisplayChildren(false);
            $candle->addChild('Create', array('route' => 'aspetos_admin_candle_create'));
            $candle->addChild('Edit', array('route' => 'aspetos_admin_candle_edit', 'routeParameters' => array('id' => $this->request->get('id', 0))));

            $condolence = $menu->addChild('Condolences', array('route' => 'aspetos_admin_condolence_list'))
                ->setAttribute('icon', 'fa fa-book')
                ->setDisplayChildren(false);
            $condolence->addChild('Create', array('route' => 'aspetos_admin_condolence_create'));
            $condolence->addChild('Edit', array('route' => 'aspetos_admin_condolence_edit', 'routeParameters' => array('id' => $this->request->get('id', 0))));
        }

        if ($this->securityContext->isGranted('ROLE_ADMIN')) {
            $mortician = $menu->addChild('Morticians', array('route' => 'aspetos_admin_mortician_mortician_list'))
                ->setAttribute('icon', 'fa fa-battery-4 fa-rotate-270')
                ->setDisplayChildren(false);
            $mortician->addChild('Create', array('route' => 'aspetos_admin_mortician_mortician_create'));
            $mortician->addChild('Edit', array('route' => 'aspetos_admin_mortician_mortician_edit', 'routeParameters' => array('id' => $this->request->get('id', 0))));
            $mortician->addChild('Detail', array('route' => 'aspetos_admin_mortician_mortician_detail', 'routeParameters' => array('id' => $this->request->get('id', 0))));

            $supplier = $menu->addChild('Supplier', array('route' => 'aspetos_admin_supplier_supplier_list'))
                ->setAttribute('icon', 'fa fa-truck');
            $supplierType = $supplier->addChild('Types', array('route' => 'aspetos_admin_supplier_type_list'))
                ->setAttribute('icon', 'fa fa-puzzle-piece')
                ->setDisplayChildren(false);
            $supplierType->addChild('Create', array('route' => 'aspetos_admin_supplier_type_create'));
            $supplierType->addChild('Edit', array('route' => 'aspetos_admin_supplier_type_edit', 'routeParameters' => array('id' => $this->request->get('id', 0))));

            $supplier->addChild('Create', array('route' => 'aspetos_admin_supplier_supplier_create'))
                ->setDisplay(false);
            $supplier->addChild('Edit', array('route' => 'aspetos_admin_supplier_supplier_edit', 'routeParameters' => array('id' => $this->request->get('id', 0))))
                ->setDisplay(false);

            $cemeteries = $menu->addChild('Cemeteries', array('route' => 'aspetos_admin_cemetery_list'))
                ->setAttribute('icon', 'asp asp-grave')
                ->setDisplayChildren(false);
            $cemeteries->addChild('Create', array('route' => 'aspetos_admin_cemetery_create'));
            $cemeteries->addChild('Edit', array('route' => 'aspetos_admin_cemetery_edit', 'routeParameters' => array('id' => $this->request->get('id', 0))));

            $product = $menu->addChild('Products', array('route' => 'aspetos_admin_product_product_list'))
                ->setAttribute('icon', 'fa fa-cubes');

            $productCategory = $product->addChild('Categories', array('route' => 'aspetos_admin_product_category_list'))
                ->setAttribute('icon', 'fa fa-puzzle-piece')
                ->setDisplayChildren(false);

            $productCategory->addChild('Create', array('route' => 'aspetos_admin_product_category_create'));
            $productCategory->addChild('Edit', array('route' => 'aspetos_admin_product_category_edit', 'routeParameters' => array('id' => $this->request->get('id', 0))));

            $product->addChild('Create', array('route' => 'aspetos_admin_product_product_create'))
                ->setDisplay(false);
            $product->addChild('Edit', array('route' => 'aspetos_admin_product_product_edit', 'routeParameters' => array('id' => $this->request->get('id', 0))))
                ->setDisplay(false);

            $users = $menu->addChild('Users', array('route' => 'aspetos_admin_user_user_list'))
                ->setAttribute('icon', 'fa fa-user');
            $users->addChild('Create', array('route' => 'aspetos_admin_user_user_create'))
                ->setDisplay(false);
            $users->addChild('Edit', array('route' => 'aspetos_admin_user_user_edit', 'routeParameters' => array('id' => $this->request->get('id', 0))))
                ->setDisplay(false);

            if ($this->securityContext->isGranted('ROLE_SUPER_ADMIN')) {
                $users->addChild('Permissions', array('route' => 'aspetos_admin_permission_list'))
                    ->setAttribute('icon', 'fa fa-lock');
            }
        }

        if ($this->securityContext->isGranted('ROLE_TRANSLATOR')) {
            $translation = $menu->addChild('Translations', array('route' => 'lexik_translation_grid'))
                ->setAttribute('icon', 'fa fa-language')
                ->setDisplayChildren(false);
            $translation->addChild('create', array('route' => 'lexik_translation_new'));
        }



        $this->dispatcher->dispatch(
            ConfigureMenuEvent::CONFIGURE,
            new ConfigureMenuEvent($this->factory, $menu)
        );

        return $menu;
    }
}
