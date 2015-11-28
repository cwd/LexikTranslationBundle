<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Twig;
use Aspetos\Model\Entity\Obituary;
use Aspetos\Service\ObituaryService;

/**
 * Class ObituaryCountExtension
 *
 * @package Aspetos\Bundle\AdminBundle\Twig
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class ObituaryCountExtension extends \Twig_Extension
{
    /**
     * @var ObituaryService
     */
    protected $obituaryService;

    /**
     * ObituaryCountExtension constructor.
     *
     * @param ObituaryService $obituaryService
     */
    public function __construct(ObituaryService $obituaryService)
    {
        $this->obituaryService = $obituaryService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('countCandles', array($this, 'countCandles')),
            new \Twig_SimpleFunction('countCondolences', array($this, 'countCondolences')),
        );
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('countCondolences', array($this, 'countCondolences')),
            new \Twig_SimpleFilter('countCandles', array($this, 'countCandles')),
        );
    }

    /**
     * @param Obituary $obituary
     * @param null     $state
     * @param null     $fromDate
     * @param null     $toDate
     * @return int
     */
    public function countCandles(Obituary $obituary, $state = null, $fromDate = null, $toDate = null)
    {
        return $this->obituaryService->getCountCandles($obituary, $state, $fromDate, $toDate);
    }

    /**
     * @param Obituary $obituary
     * @param null     $state
     * @param null     $fromDate
     * @param null     $toDate
     * @return int
     */
    public function countCondolences(Obituary $obituary, $state = null, $fromDate = null, $toDate = null)
    {
        return $this->obituaryService->getCountCondolences($obituary, $state, $fromDate, $toDate);
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_twig_obituary_count';
    }
}

