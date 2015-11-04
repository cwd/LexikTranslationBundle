<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Forms\Product;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProductCategory Form
 *
 * @package Aspetos\Bundle\AdminBundle\Forms\Product
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_product_category")
 * @DI\Tag("form.type")
 */
class ProductCategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return misc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Name'))
            ->add('image', 'cwd_image_type', array(
                'label' => 'Image',
                'attr' => array(
                    'imagecols' => 6,
                ),
            ))
            ->add('save', 'submit', array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary')));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups'     => array('default'),
            'data_class'            => 'Aspetos\Model\Entity\ProductCategory',
            'cascade_validation'    => true,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_product_category';
    }
}
