<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cwd\MediaBundle\Service;

use Cwd\GenericBundle\Service\Generic;
use Doctrine\ORM\EntityManager;
use Gregwar\Image\Image;
use Monolog\Logger;

/**
 * Class MediaService
 *
 * @package CwdMediaBundel\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class MediaService extends Generic
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @param EntityManager $entityManager
     * @param Logger        $logger
     * @param array         $config
     *
     * @throws \Exception
     */
    public function __construct(EntityManager $entityManager, Logger $logger, $config)
    {
        $this->config = $config;
        $this->debug  = $config['throw_exception'];

        parent::__construct($entityManager, $logger);

        $this->directorySetup();
    }

    /**
     * @param null|string $key
     *
     * @return string|array
     */
    public function getConfig($key = null)
    {
        if ($key !== null && isset($this->config[$key])) {
            return $this->config[$key];
        }

        return $this->config;
    }

    /**
     * @param string $input
     *
     * @throws \Exception
     * @return string
     */
    public function storeImage($input)
    {
        if (!file_exists($input) || !is_readable($input)) {
            throw new \Exception('File does not exists or is not readable - '.$input);
        }

        $md5    = md5_file($input);
        $path   = $this->createDirectoryByFilename($md5);
        $target = $path.'/'.$md5.'.jpg';

        Image::open($input)
            ->cropResize($this->getConfig('converter')['size']['max_width'], $this->getConfig('converter')['size']['max_height'])
            ->save($target, 'jpeg', $this->getConfig('converter')['quality']);

        list($width, $height, $type, $attr) = getimagesize($target);

        $result = array(
            'path' => $this->getRelativePath($target),
            'width' => $width,
            'height' => $height,
            'type' => $type
        );

        dump($result);
        return $result;
    }

    /**
     * get relative path from "dirname"
     * @param $path
     *
     * @return mixed
     */
    protected function getRelativePath($path)
    {
        return str_replace($this->getConfig('storage')['path'].'/', '', $path);
    }

    /**
     * @param string $md5
     *
     * @return string
     */
    protected function createDirectoryByFilename($md5)
    {
        $depth = $this->getConfig('storage')['depth'];
        $path  = $this->getConfig('storage')['path'];

        for ($i = 0; $i < $depth; $i++) {
            $path = $this->createDirectory($path, $md5[$i]);
        }

        return $path;
    }

    /**
     * @param string $path
     * @param int    $idx
     *
     * @return string
     */
    protected function createDirectory($path, $idx)
    {
        if (!is_dir($path.'/'.$idx)) {
            mkdir($path.'/'.$idx);
        }

        return $path.'/'.$idx;
    }

    /**
     * @throws \Exception
     */
    protected function directorySetup()
    {
        if (!is_dir($this->getConfig('storage')['path'])) {
            mkdir($this->getConfig('storage')['path']);
        } elseif (!is_writeable($this->getConfig('storage')['path'])) {
            throw new \Exception('Storage Path not writeable');
        }

        if (!is_dir($this->getConfig('cache')['path'].'/'.$this->getConfig('cache')['dirname'])) {
            mkdir($this->getConfig('cache')['path'].'/'.$this->getConfig('cache')['dirname']);
        } elseif (!is_writeable($this->getConfig('cache')['path'].'/'.$this->getConfig('cache')['dirname'])) {
            throw new \Exception('Cache Path not writeable');
        }
    }

}
