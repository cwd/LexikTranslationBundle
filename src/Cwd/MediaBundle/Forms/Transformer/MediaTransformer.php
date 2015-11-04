<?php
/*
 * This file is part of cwd media bundle.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cwd\MediaBundle\Forms\Transformer;

use Cwd\MediaBundle\MediaException;
use Cwd\MediaBundle\Model\Entity\Media;
use Cwd\MediaBundle\Service\MediaService;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class MediaTransformer
 *
 * @package Cwd\MediaBundle\Form\Transformer
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class MediaTransformer implements DataTransformerInterface
{
    /**
     * @var MediaService
     */
    private $mediaService;

    /**
     * @param MediaService $mediaService
     */
    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }


    /**
     * Transforms an MediaObject ... nothing.
     *
     * @param  Media|null $media
     * @return Media
     */
    public function transform($media)
    {
        if (null === $media) {
            return null;
        }

        return $media;
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $mediaFile
     * @return Media|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($mediaFile)
    {
        if (!$mediaFile || $mediaFile === null) {
            return;
        }
        if ($mediaFile instanceOf UploadedFile) {
            try {
                $media = $this->mediaService->create($mediaFile->getPathname(), true);

                return $media;

            } catch (MediaException $e) {
                throw new TransformationFailedException('Uploaded file could not be stored', 0, $e);
            }
        } elseif (is_numeric($mediaFile)) {
            return $this->mediaService->find($mediaFile);
        }

        return null;
    }
}
