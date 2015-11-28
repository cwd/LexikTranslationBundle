<?php
/*
 * This file is part of cwd media bundle.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cwd\MediaBundle\Forms\Type;

use Cwd\MediaBundle\Forms\Transformer\MediaTransformer;
use Cwd\MediaBundle\Service\MediaService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ImageType
 *
 * @package Cwd\MediaBundle\Form\Type
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class ImageType extends AbstractType
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
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new MediaTransformer($this->mediaService);
        $builder->addModelTransformer($transformer);
        //$builder->addViewTransformer($transformer);

        parent::buildForm($builder, $options);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'validation_groups'     => array('default'),
                'data_class'            => 'Cwd\MediaBundle\Model\Entity\Media',
                'cascade_validation'    => true
            )
        );
    }

    /**
     *
     * @return string
     */
    public function getParent()
    {
        return 'file';
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'cwd_image_type';
    }

}
