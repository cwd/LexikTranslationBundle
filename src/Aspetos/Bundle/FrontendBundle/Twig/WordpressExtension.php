<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\FrontendBundle\Twig;

use Cwd\WordpressApiBundle\Service\WordpressApi;

/**
 * Class WordpressExtension
 *
 * @package Aspetos\Bundle\FrontendBundle\Twig
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class WordpressExtension extends \Twig_Extension
{
    /**
     * @var WordpressApi
     */
    protected $wordpressApi;

    /**
     * @param WordpressApi $wordpressApi
     */
    public function __construct(WordpressApi $wordpressApi)
    {
        $this->wordpressApi = $wordpressApi;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'latestNews',
                array(
                    $this,
                    'latestNews'
                ),
                array(
                    'is_safe'           => array('html'),
                    'needs_environment' => true
                )
            ),
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @return string
     */
    public function latestNews(\Twig_Environment $twig)
    {
        $posts = $this->wordpressApi->posts();

        return $twig->render('AspetosFrontendBundle:Wordpress:news_list.html.twig', array('posts' => $posts));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'aspetos_wordpress_extension';
    }
}

