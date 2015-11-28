<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\ShopBundle\Form\Type;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * CustomerOrder form type.
 *
 * @package Aspetos\Bundle\ShopBundle\Form\Type
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_shop_checkout")
 * @DI\Tag("form.type")
 */
class CheckoutType extends AbstractType
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
            ->add('orderItems', 'collection', array(
                'label'              => false,
                'type'               => 'aspetos_shop_order_item',
                'required'           => false,
                'allow_add'          => false,
                'allow_delete'       => true,
                'by_reference'       => false,
                'cascade_validation' => true,
                'options'            => array(
                    'label'              => false,
                ),
                'attr'               => array(),
            ))
            ->add('continue', 'submit', array(
                'label' => 'Einkauf fortsetzen',
                'icon' => 'fa fa-shopping-cart',
                'attr' => array(
                    'class' => 'btn btn-default',
                ),
            ))
            ->add('checkout', 'submit', array(
                'label' => 'Zur Kasse',
                'icon' => 'fa fa-check',
                'attr' => array(
                    'class' => 'btn btn-primary',
                ),
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'validation_groups' => array('default'),
                'data_class' => 'Aspetos\Model\Entity\CustomerOrder',
                'cascade_validation' => true,
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_shop_checkout';
    }
}
