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

use Aspetos\Service\Exception\NotFoundException;
use Cwd\GenericBundle\Service\Generic;
use Cwd\MediaBundle\MediaException;
use Cwd\MediaBundle\Model\Entity\Media;
use Doctrine\DBALMediaException\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Gregwar\Image\Image;
use Psr\Log\LoggerInterface;

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
     * @param EntityManager   $entityManager
     * @param LoggerInterface $logger
     * @param array           $config
     *
     * @throws MediaException
     */
    public function __construct(EntityManager $entityManager, LoggerInterface $logger, $config)
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
     * @throws MediaException
     */
    public function getConfig($key = null)
    {
        if ($key !== null && isset($this->config[$key])) {
            return $this->config[$key];
        } elseif ($key !== null && !isset($this->config[$key])) {
            throw new MediaException($key.' not set');
        }

        return $this->config;
    }

    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @param string $imagePath
     * @param bool   $searchForExisting
     *
     * @return Media
     * @throws MediaException
     */
    public function create($imagePath, $searchForExisting=false)
    {
        try {
            $media = $this->findByMd5(md5_file($imagePath));

            if ($media !== null && $searchForExisting) {
                return $media;
            }

            if (!$searchForExisting) {
                throw new MediaException('MD5 already in DB - use searchForExisting');
            }
        } catch (EntityNotFoundException $e) {
            $imageData = $this->storeMedia($imagePath);
            $media = $this->getNewMediaObject();

            $media->setFilehash($imageData['md5'])
                  ->setFilename($imageData['path'])
                  ->setMediatype($imageData['type']);

            $this->getEm()->persist($media);
        }

        return $media;
    }

    /**
     * @param Media    $media
     * @param int|null $width
     * @param int|null $height
     *
     * @return Image
     * @throws MediaException
     */
    public function createInstance(Media $media, $width = null, $height = null)
    {
        if ($media->getMediatype() == 'application/pdf') {
            // Convert to an image first
            $file = $this->pdfToImage($media);
            if ($file === null) {
                return null;
            }
            $image = new Image($file, $width, $height);
            unlink($file);
        } else {
            $image = new Image($this->getFilePath($media), $width, $height);
        }

        $image->setCacheDir('/'.$this->getConfig('cache')['dirname']);
        $image->setCacheDirMode('0755');
        $image->setActualCacheDir($this->getConfig('cache')['path'].'/'.$this->getConfig('cache')['dirname']);

        return $image;
    }

    protected function pdfToImage(Media $media)
    {
        try {
            $file = tempnam('/tmp', 'aspetos-pdf2jpg');

            $img = new \Imagick();
            $img->setResolution(300, 300);
            $img->readImage($this->getFilePath($media));
            $img->setImageFormat('jpg');
            $image = $img->writeImage($file);

            if ($image === true) {
                throw new \Exception('Error converting pdf to jpg');
            }

            return $image;
        } catch (\Exception $e) {
            dump($e->getMessage());
            throw new MediaException($e);
        }

        return null;
    }

    /**
     * @param Media $media
     *
     * @return string
     * @throws MediaException
     */
    protected function getFilePath(Media $media)
    {
        return $this->getConfig('storage')['path'].'/'.$media->getFilename();
    }


    /**
     * @param string $md5
     *
     * @return Media
     * @throws EntityNotFoundException
     * @throws MediaException
     */
    public function findByMd5($md5)
    {
        $object = $this->findOneByFilter($this->getConfig('entity_class'), array('filehash' => $md5));

        if ($object === null) {
            throw new EntityNotFoundException();
        }

        return $object;
    }

    /**
     *
     * @return Media
     * @throws MediaException
     */
    protected function getNewMediaObject()
    {
        $class = '\\'.$this->getEm()->getRepository($this->getConfig('entity_class'))->getClassName();

        return new $class;
    }

    /**
     * @param string $input
     *
     * @return array|null|string
     * @throws MediaException
     */
    public function storeMedia($input)
    {
        if (!file_exists($input) || !is_readable($input)) {
            throw new MediaException('File does not exists or is not readable - '.$input);
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $input);

        switch ($mime) {
            case 'image/jpeg':
            case 'image/png':
            case 'image/tiff':
            case 'image/gif':
            case 'image/webp':
                return $this->storeImage($input);
                break;
            case 'application/pdf':
            default:
                return $this->storeFile($input, $mime);
        }

        return null;
    }

    /**
     * @param string $input
     * @param string $mime
     *
     * @return array
     */
    public function storeFile($input, $mime)
    {
        $md5    = md5_file($input);
        $path   = $this->createDirectoryByFilename($md5);
        $target = $path.'/'.$md5.'.'.$this->mimeToExtension($mime);

        copy($input, $target);

        $result = array(
            'path'   => $this->getRelativePath($target),
            'md5'    => $md5,
            'width'  => null,
            'height' => null,
            'type'   => $mime
        );

        return $result;
    }

    /**
     * @param $mime
     *
     * @return string
     */
    protected function mimeToExtension($mime)
    {
        $map = array(
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/tiff' => 'tif',
            'image/webp' => 'webp',
            'application/pdf' => 'pdf'
        );

        return (isset($map[$mime])) ? $map[$mime] : '.unknown';
    }

    /**
     * @param string $input
     *
     * @throws MediaException
     * @return string
     */
    public function storeImage($input)
    {


        $md5    = md5_file($input);
        $path   = $this->createDirectoryByFilename($md5);
        $target = $path.'/'.$md5.'.jpg';

        Image::open($input)
            ->setForceCache(false)
            ->cropResize($this->getConfig('converter')['size']['max_width'], $this->getConfig('converter')['size']['max_height'])
            ->save($target, 'jpeg', $this->getConfig('converter')['quality']);

        list($width, $height, $type, $attr) = getimagesize($target);

        $result = array(
            'path'   => $this->getRelativePath($target),
            'md5'    => $md5,
            'width'  => $width,
            'height' => $height,
            'type'   => image_type_to_mime_type($type)
        );

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
     * @throws MediaException
     */
    protected function createDirectoryByFilename($md5)
    {
        $this->directorySetup();
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
     * @throws MediaException
     */
    protected function directorySetup()
    {
        if (!is_dir($this->getConfig('storage')['path'])) {
            mkdir($this->getConfig('storage')['path']);
        } elseif (!is_writeable($this->getConfig('storage')['path'])) {
            throw new MediaException('Storage Path not writeable');
        }

        if (!is_dir($this->getConfig('cache')['path'].'/'.$this->getConfig('cache')['dirname'])) {
            mkdir($this->getConfig('cache')['path'].'/'.$this->getConfig('cache')['dirname']);
        } elseif (!is_writeable($this->getConfig('cache')['path'].'/'.$this->getConfig('cache')['dirname'])) {
            throw new MediaException('Cache Path not writeable');
        }
    }

    /**
     * Find Object by ID
     *
     * @param int $pid
     *
     * @return Entity
     * @throws NotFoundException
     */
    public function find($pid)
    {
        try {
            $obj = parent::findById('Model:Media', intval($pid));

            if ($obj === null) {
                $this->getLogger()->info('Row with ID {id} not found', array('id' => $pid));
                throw new NotFoundException('Row with ID ' . $pid . ' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw new NotFoundException();
        }
    }
}
