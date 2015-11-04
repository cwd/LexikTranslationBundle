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

use Aspetos\Model\Repository\ProductCategoryRepository;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Product Form
 *
 * @package Aspetos\Bundle\AdminBundle\Forms
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_product_product")
 * @DI\Tag("form.type")
 */
class ProductType extends AbstractType
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
            ->add('description', 'textarea', array('label' => 'Description'))
            ->add('basePrice', 'money', array('label' => 'Base Price'))
            ->add('sellPrice', 'money', array('label' => 'Selling Price'))
            ->add('mainImage', 'cwd_image_type', array(
                'label' => 'Image',
                'attr' => array(
                    'imagecols' => 6,
                ),
            ))
            ->add('productHasCategory', 'collection', array(
                'type'               => 'aspetos_admin_form_product_category',
                'required'           => false,
                'allow_add'          => true,
                'allow_delete'       => true,
                'by_reference'       => false,
                'cascade_validation' => true,
                'label'              => 'Categories',
                'options'            => array(),
                'attr'               => array(
                    'class' => 'collection-holder'
                ),
                'options' => array(
                    'label' => false
                )
            ))
            ->add('save', 'submit', array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary')));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('default'),
            'data_class' => 'Aspetos\Model\Entity\Product',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_product_product';
    }
}
