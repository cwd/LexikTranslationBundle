<?php
namespace Aspetos\Bundle\FrontendBundle\Controller;

use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cwd\WordpressApiBundle\Service\WordpressApi;

/**
 * Class WordpressController
 *
 * @package Aspetos\Bundle\FrontendBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 * @Route("/")
 */
class WordpressController extends Controller
{
    /**
     * @Route("/post/{slug}/{id}")
     * @Template()
     *
     * @param string $slug
     * @param int    $id
     * @return array
     */
    public function postAction($slug, $id)
    {
        $wordpressApiService = $this->container->get('cwd.wordpressapi');
        $post = $wordpressApiService->post($id);

        return array('post' => $post);
    }

    /**
     * @Route("/category/{slug}/{id}")
     * @Template()
     *
     * @param string $slug
     * @param int    $id
     * @return array
     */
    public function categoryAction($slug, $id)
    {
        $wordpressApiService = $this->container->get('cwd.wordpressapi');
        $posts = $wordpressApiService->posts($id);
        $category = $wordpressApiService->taxonomyTerm('category', $id);

        return array(
            'category'  => $category,
            'posts'     => $posts
        );
    }

    /**
     * @Route("/wp-test")
     * @Template()
     * @return array()
     */
    public function wpTestAction()
    {
        $wordpressApiService = $this->container->get('cwd.wordpressapi');
        $result = $wordpressApiService->posts();
        //dump($result);

        $result = $wordpressApiService->posts(5);
        dump($result);

        $result = $wordpressApiService->post(1068);
        //dump($result);

        $result = $wordpressApiService->postMeta(1068);
        //dump($result);

        $result = $wordpressApiService->taxonomies();
        //dump($result);

        $result = $wordpressApiService->taxonomy('category');
        //dump($result);

        $result = $wordpressApiService->taxonomyTerms('category');
        //dump($result);

        $result = $wordpressApiService->taxonomyTerm('category', 722);
        //dump($result);

        $result = $wordpressApiService->postTypes();
        //dump($result);

        $result = $wordpressApiService->menus();
        //dump($result);

        $result = $wordpressApiService->menu(934);
        dump($result);
        die();

        return array();
    }
}
