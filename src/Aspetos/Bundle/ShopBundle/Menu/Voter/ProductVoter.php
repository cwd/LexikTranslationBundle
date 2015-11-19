<?php
/*
 * This file is part of Aspetos
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\ShopBundle\Menu\Voter;

use Aspetos\Service\Exception\ProductNotFoundException;
use Aspetos\Service\ProductService;
use JMS\DiExtraBundle\Annotation as DI;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Menu voter to highlight correct category in product detail pages.
 *
 * @package Aspetos\Bundle\ShopBundle\Menu\Voter
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.shop.menu_voter.product", public=false)
 * @DI\Tag("knp_menu.voter")
 */
class ProductVoter implements VoterInterface
{
    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var bool|Product
     */
    protected $product;

    /**
     * @var string[]
     */
    protected $categorySlugs;

    /**
     * @DI\InjectParams({
     *      "requestStack" = @DI\Inject("request_stack"),
     *      "productService" = @DI\Inject("aspetos.service.product.product")
     * })
     *
     * @param RequestStack   $requestStack
     * @param ProductService $productService
     */
    public function __construct(RequestStack $requestStack, ProductService $productService)
    {
        $this->request = $requestStack->getMasterRequest();
        $this->productService = $productService;
    }

    /**
     * Checks whether an item is current.
     *
     * If the voter is not able to determine a result,
     * it should return null to let other voters do the job.
     *
     * @param ItemInterface $item
     *
     * @return boolean|null
     */
    public function matchItem(ItemInterface $item)
    {
        $this->fetchCategories();
        if (empty($this->categorySlugs)) {
            return;
        }

        foreach ($item->getExtra('routes', array()) as $routeExtra) {
            if ('aspetos_shop_category' !== $routeExtra['route'] || !isset($routeExtra['parameters']['slug'])) {
                continue;
            }

            if (isset($this->categorySlugs[$routeExtra['parameters']['slug']])) {
                return true;
            }
        }
    }

    /**
     * Fetch ProductCategory information from request.
     * After this call the $categorySlugs property will contain the slugs of the product's categories IF
     * the current page is a product detail page.
     */
    protected function fetchCategories()
    {
        if (null !== $this->categorySlugs) {
            return;
        }

        $this->categorySlugs = array();

        if ('aspetos_shop_product' !== $this->request->get('_route')) {
            return;
        }

        try {
            $product = $this->productService->findOneBySlug($this->request->get('slug'));
        } catch (ProductNotFoundException $e) {
            return;
        }

        foreach ($product->getCategories() as $category) {
            $this->categorySlugs[$category->getSlug()] = $category->getSlug();
        }
    }
}
