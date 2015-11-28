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
 * OrderItem form type.
 *
 * @package Aspetos\Bundle\ShopBundle\Form\Type
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_shop_order_item")
 * @DI\Tag("form.type")
 */
class OrderItemType extends AbstractType
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
            ->add('product', 'entity_id', array(
                'class' => 'Model:Product',
            ))
            ->add('amount', 'number', array(
                'label' => false,
                'attr' => array(
                    'readonly' => 'readonly',
                    'class' => 'form-control input-sm',
                ),
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'validation_groups' => array('default'),
                'data_class' => 'Aspetos\Model\Entity\OrderItem',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_shop_order_item';
    }
}
