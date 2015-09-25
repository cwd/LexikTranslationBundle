<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Vtiger;

use Cwd\VtigerBundle\Service\VtigerClient;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class Importer
 *
 * @package Cwd\VtigerBundle\Service\VtigerClient
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.vtiger.importer")
 */
class Importer
{
    protected $customFieldMap = array(
        'CUSTOMER_SINCE' => 'cf_763',
        'CUSTOMER_UNTIL' => 'cf_765',
        'CUSTOMER'       => 'cf_771',
        'UID'            => 'cf_908'
    );

    /**
     * @var VtigerClient
     */
    protected $client;

    /**
     * @param VtigerClient $client
     *
     * @DI\InjectParams({
     *     "client" = @DI\Inject("cwd.vtiger.client")
     * })
     */
    public function __construct(VtigerClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return \stdClass
     * @throws \Exception
     */
    public function getClients($limit = 1, $offset = 0)
    {
        $query = sprintf("SELECT * FROM Accounts WHERE %s='%s' LIMIT %s,%s;", $this->getCustomField('CUSTOMER'), 'ja', $offset, $limit);

        return $this->client->query($query);
    }

    protected function getCustomField($name)
    {
        if (!isset($this->customFieldMap[$name])) {
            throw new \Exception('No such customField by this name');
        }

        return $this->customFieldMap[$name];
    }
}
