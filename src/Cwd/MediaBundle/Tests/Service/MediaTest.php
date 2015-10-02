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
        $this->assertTrue(is_dir($this->service->getConfig('storage')['path']));
        $this->assertTrue(is_dir($this->service->getConfig('cache')['path']));
        $this->assertTrue(is_writeable($this->service->getConfig('storage')['path']));
        $this->assertTrue(is_writeable($this->service->getConfig('cache')['path']));

        try {
            $repository = $this->getEntityManager()->getRepository($this->service->getConfig('entity_class'));
            $this->assertInstanceOf('Cwd\MediaBundle\Model\Repository\MediaRepository', $repository);
        } catch (\Exception $e) {
            $this->assertTrue(false, $this->service->getConfig('entity_class').' is not a valid Repository');

        }
    }

}
