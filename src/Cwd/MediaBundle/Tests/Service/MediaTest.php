<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CwdMediaBundle\Tests\Service;

use Cwd\GenericBundle\Tests\Repository\DoctrineTestCase;

/**
 * Class MediaTest
 *
 * @package CwdMediaBundle\Tests\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class MediaTest extends DoctrineTestCase
{
    /**
     * @var \CwdMediaBundle\Service\MediaService
     */
    protected $service;

    /**
     * @var string
     */
    protected $tmpDir;

    public function setUp()
    {
        $this->service = $this->container->get('cwd.media.service');
        $this->tmpDir = sys_get_temp_dir();

    }

    public function testSetup()
    {
        dump($this->service->getConfig());
    }

}
