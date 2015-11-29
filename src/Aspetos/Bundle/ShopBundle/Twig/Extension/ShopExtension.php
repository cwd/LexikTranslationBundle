<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\ShopBundle\Twig\Extension;

use JMS\DiExtraBundle\Annotation as DI;
use Aspetos\Service\Shop\Shop;

/**
 * Class ImageExtension
 *
 * @package Aspetos\Bundle\ShopBundle\Twig\Extension
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.shop.twig_extension")
 * @DI\Tag("twig.extension")
 */
class ShopExtension extends \Twig_Extension
{
    /**
     * @var Shop
     */
    protected $shop;

    /**
     * @DI\InjectParams({
     *      "shop" = @DI\Inject("aspetos.shop")
     * })
     *
     * @param Shop $shop
     */
    public function __construct(Shop $shop)
    {
        $this->shop = $shop;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('VAT', array($this, 'getFormattedVatRate')),
        );
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('grossPrice', array($this, 'formatGrossPrice'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('vatPrice', array($this, 'formatVatPrice'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('price', array($this, 'formatPrice'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Format the given price, including currency.
     *
     * @param mixed $value
     * @param bool  $wrapCurrency Set to false to disable <span>-wrapping of currency symbol
     * @return string
     */
    public function formatPrice($value, $wrapCurrency = true)
    {
        $format = $wrapCurrency ? '<span>%s</span>%.2f' : '%s%.2f';

        return sprintf($format, $this->shop->getCurrencySymbol(), $value);
    }

    /**
     * Format given price, including currency, calculating gross value including VAT from net value first.
     *
     * @param mixed $value
     * @param bool  $wrapCurrency Set to false to disable <span>-wrapping of currency symbol
     * @return string
     */
    public function formatGrossPrice($value, $wrapCurrency = true)
    {
        return $this->formatPrice($this->shop->net2gross($value), $wrapCurrency);
    }

    /**
     * Format VAT part of given price, including currency, calculating VAT from net value first.
     *
     * @param mixed $value
     * @param bool  $wrapCurrency Set to false to disable <span>-wrapping of currency symbol
     * @return string
     */
    public function formatVatPrice($value, $wrapCurrency = true)
    {
        return $this->formatPrice($this->shop->net2vat($value), $wrapCurrency);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_shop';
    }

    /**
     * Get current VAT rate, formatted, including percentage symbol.
     *
     * @return string
     */
    public function getFormattedVatRate()
    {
        $vat = sprintf('%.2f', $this->shop->getVatRate());
        $vat = rtrim($vat, '0');
        $vat = rtrim($vat, '.');

        return $vat.'%';
    }
}
