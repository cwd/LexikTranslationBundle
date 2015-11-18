<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cwd\WordpressApiBundle\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * Class WordpressApiService
 *
 * @package Cwd\WordpressApiBundle\Service
 * @author  Niklas de Vries <nd@cwd.at>
 */
class WordpressApi
{
    /**
     * @var String
     */
    protected $apiurl;

    /**
     * @var String
     */
    protected $username;

    /**
     * @var String
     */
    protected $password;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param array $config
     *
     * @throws \Exception
     */
    public function __construct($config)
    {
        if (!isset($config['apiurl']) ||
            !isset($config['username']) ||
            !isset($config['password'])) {
            throw new \Exception('WordpressApiService: not all required config values are set');
        }

        $this->apiurl   = $config['apiurl'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->client   = new Client();
    }

    /**
     * @param null   $categoryId
     * @param string $postType
     * @return array
     */
    public function posts($categoryId = null, $postType = 'post')
    {
        $filter = array(
            'post_type' => $postType
        );

        if ($categoryId !== null) {
            $filter['cat'] = $categoryId;
        }

        return $this->request('posts', ['filter' => $filter]);
    }

    /**
     * @return array
     */
    public function postTypes()
    {
        return $this->request('posts/types');
    }

    /**
     * @param int $postId
     * @return array
     */
    public function post($postId = null)
    {
        return $this->request('posts/' . $postId);
    }

    /**
     * @param int $postId
     * @return array
     */
    public function postMeta($postId = null)
    {
        return $this->request('posts/' . $postId . '/meta');
    }

    /**
     * @return array
     */
    public function taxonomies()
    {
        return $this->request('taxonomies');
    }

    /**
     * @param string $taxonomy
     * @return array
     */
    public function taxonomy($taxonomy)
    {
        return $this->request('taxonomies/' . $taxonomy);
    }

    /**
     * @param string $taxonomy
     * @return array
     */
    public function taxonomyTerms($taxonomy)
    {
        $terms = $this->request('taxonomies/' . $taxonomy . '/terms');

        return $this->transformToHierachy($terms);
    }

    /**
     * @param string $taxonomy
     * @param int    $termId
     * @return array
     */
    public function taxonomyTerm($taxonomy, $termId)
    {
        return $this->request('taxonomies/' . $taxonomy . '/terms/' . $termId);
    }

    /**
     * @return array
     */
    public function menus()
    {
        return $this->request('menus');
    }

    /**
     * @param int $menuId
     * @return array
     */
    public function menu($menuId)
    {
        $menu = $this->request('menus/' . $menuId);
        if (isset($menu['items'])) {
            $menu['items'] = $this->transformToHierachy($menu['items']);
        }

        return $menu;
    }

    /**
     * @param string $operation
     * @param array  $params
     *
     * @return array
     * @throws \Exception
     */
    protected function request($operation, $params = array())
    {
        try {
            $response = $this->client->get(
                $this->apiurl . $operation,
                [
                    'query' => $params,
                    'auth' => [
                        $this->username,
                        $this->password
                    ]
                ]
            );
        } catch (\Exception $e) {
            dump($e);
        }

        return $this->responseHandler($response);
    }

    /**
     * @param Response $response
     * @return array
     * @throws \Exception
     */
    protected function responseHandler(Response $response)
    {
        if ($response->getStatusCode() != 200) {
            throw new \Exception('wordpressApi: Expected 200 return code - got '.$response->getStatusCode());
        }

        $result = json_decode($response->getBody()->getContents(), true);

        if ($result !== null) {
            return $result;
        }

        throw new \Exception('wordpressApi: Something went wrong - result was '.$response->getBody()->getContents());
    }

    /**
     * @param $items
     * @param $parentId
     * @param string $idKey
     * @param string $parentKey
     * @param string $childKey
     * @return array
     */
    protected function transformToHierachy($items, $idKey = 'ID', $parentKey = 'parent', $childKey = 'children', $parentId = 0)
    {
        $hierarchical = array();

        foreach ($items as $i => $item) {
            if ($item[$parentKey] == $parentId) {
                $hierarchical[$item[$idKey]] = $item;
                unset($items[$i]);
            }
        }

        foreach ($hierarchical as $i => $item) {
            $hierarchical[$i][$childKey] = $this->transformToHierachy($items, $idKey, $parentKey, $childKey, $item[$idKey]);
        }

        return $hierarchical;
    }
}
