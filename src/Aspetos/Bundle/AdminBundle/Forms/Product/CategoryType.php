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
 * @package Aspetos\Bundle\AdminBundle\Forms\Address
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_product_category")
 * @DI\Tag("form.type")
 */
class CategoryType extends AbstractType
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
            ->add('productCategory', 'entity', array(
                    'label'         => 'Category',
                    'class'         => 'Model:ProductCategory',
                    'choice_label'  => 'name',
                    'placeholder'   => 'Select category',
                    'query_builder' => function (ProductCategoryRepository $repository){
                        $builder = $repository->createQueryBuilder('c');
                        $builder->orderBy('c.name', 'ASC');

                        return $builder;
                    }
                )
            )
            ->add('sort', 'number', array('label' => 'Sort position'));

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
                'data_class' => 'Aspetos\Model\Entity\ProductHasCategory',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_product_category';
    }
}
