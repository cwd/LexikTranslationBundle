<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cwd\VtigerBundle\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * Class VtigerClient
 *
 * @package Cwd\VtigerBundle\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class VtigerClient
{
    protected $urlEndpoint = null;

    protected $username = null;

    protected $accessKey = null;

    protected $session = null;

    protected $validUntil = 0;

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
            !isset($config['accesskey'])) {
            throw new \Exception('VtigerClient: not all required config values are set');
        }

        $this->urlEndpoint = $config['apiurl'];
        $this->username    = $config['username'];
        $this->accessKey   = $config['accesskey'];
        $this->client      = new Client();

        $this->login();
    }

    /**
     * @return \stdClass
     */
    public function listTypes()
    {
        return $this->request('listtypes', 'GET');
    }

    /**
     * @param string $type
     *
     * @return \stdClass
     */
    public function describe($type)
    {
        return $this->request('describe', 'GET', array(
            'elementType' => $type
        ));
    }

    /**
     * @param int $id
     *
     * @return \stdClass
     */
    public function get($id)
    {
        return $this->request('retrieve', 'GET', array(
            'id' => $id
        ));
    }

    /**
     * @param string $query
     *
     * @return \stdClass
     */
    public function query($query)
    {
        return $this->request('query', 'GET', array(
            'query' => $query
        ));
    }

    protected function login()
    {
        $token = $this->getChallangeToken();
        $result = $this->request('login', 'POST', array(
            'username'  => $this->username,
            'accessKey' => md5($token.$this->accessKey)
        ));

        if (!isset($result->sessionName)) {
            throw new \Exception('vtiger: Unexpected result on login: '.json_encode($result));
        }

        $this->session = $result->sessionName;
    }

    /**
     * Get Challenge Token for login
     * @return string
     */
    protected function getChallangeToken()
    {
        $result = $this->request('getchallenge', 'GET', array('username' => $this->username));

        $this->validUntil = $result->expireTime;

        return $result->token;
    }

    /**
     * Extends session
     * @return \stdClass
     */
    public function extendSession()
    {
        return $this->request('extendsession', 'GET');
    }

    /**
     * @param string $operation
     * @param string $method
     * @param array  $params
     *
     * @return \stdClass
     * @throws \Exception
     */
    protected function request($operation, $method = 'GET', $params = array())
    {
        // if session has timedout - refresh login silently
        if (!in_array($operation, array('getchallenge', 'login')) && $this->validUntil < date("U")) {
            $this->login();
        } elseif (!in_array($operation, array('getchallenge', 'login'))) {
            $params['sessionName'] = $this->session;
        }

        $params['operation'] = $operation;

        if ($method == 'GET') {
            $response = $this->client->get($this->urlEndpoint, array(
                'query' => $params
            ));
        } elseif ($method == 'POST') {
            $response = $this->client->post($this->urlEndpoint, array(
                'form_params' => $params
            ));
        }

        return $this->responseHandler($response);
    }

    protected function responseHandler(Response $response)
    {
        if ($response->getStatusCode() != 200) {
            throw new \Exception('vtiger: Expected 200 return code - got '.$response->getStatusCode());
        }

        $result = json_decode($response->getBody()->getContents());

        if (isset($result->success) && $result->success) {
            return $result->result;
        }

        if (isset($result->success) && !$result->success && isset($result->error)) {
            throw new \Exception('vtiger: bad request - '.$result->error->message);
        }
        throw new \Exception('vtiger: Something went wrong - result was '.$response->getBody()->getContents());
    }
}
