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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProductCategory Form
 *
 * @package Aspetos\Bundle\AdminBundle\Forms\Product
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_product_availability")
 * @DI\Tag("form.type")
 */
class ProductAvailabilityType extends AbstractType
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
            ->add('country', 'choice', array(
                'choices' => array(
                    'AT' => 'AT',
                    'DE' => 'DE'
                ),
                'attr' => array(
                    'label' => 'Country'
                )
            ))
            ->add('state', 'checkbox', array(
                'label' => false,
                'attr' => array(
                    'data-on-text' => '<i class="fa fa-unlock"></i>',
                    'data-off-text' => '<i class="fa fa-lock"></i>',
                    'data-size' => 'large',
                    'data-on-color' => 'success',
                    'data-off-color' => 'danger',
                    'class' => 'make-switch',
                    'align_with_widget' => true
                )
            ));

        return $builder;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'validation_groups' => array('default'),
                'data_class' => 'Aspetos\Model\Entity\ProductAvailability',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_product_availability';
    }
}
