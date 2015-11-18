<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Forms\Supplier;

use Aspetos\Model\Entity\Supplier;
use Aspetos\Model\Repository\CemeteryRepository;
use Aspetos\Model\Repository\SupplierRepository;
use Aspetos\Model\Repository\SupplierTypeRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Supplier Form
 *
 * @package Aspetos\Bundle\AdminBundle\Forms\Supplier
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_supplier_supplier")
 * @DI\Tag("form.type")
 */
class SupplierType extends AbstractType
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
            ->add(
                'parentSupplier',
                'entity',
                array(
                    'class'        => 'Model:Supplier',
                    'choice_label' => 'formattedName',
                    'label'        => 'Parent Supplier',
                    'placeholder'  => '',
                    'empty_data'   => null,
                    'attr'         => array(
                        'class' => 'select2',
                        'data-placeholder' => 'Select parent supplier'
                    ),
                    'query_builder' => function (SupplierRepository $repository){
                        $builder = $repository->createQueryBuilder('s');
                        $builder->select('s', 'a')
                            // join, so we dont have 1+n query for the address we dont use anyway
                            ->join('s.address', 'a', Query\Expr\Join::LEFT_JOIN)
                            ->orderBy('s.name', 'ASC');

                        return $builder;
                    }
                )
            )
            ->add('vat', 'text', array('label' => 'Vat'))
            ->add('phone', 'tel', array(
                'label' => 'Phone',
                'attr'  => array(
                    'help' => "Format +43 1 123123"
                )
            ))
            ->add('fax', 'tel', array(
                'label' => 'Fax',
                'attr'  => array(
                    'help' => "Format +43 1 123123"
                )
            ))
            ->add('email', 'email', array('label' => 'Email'))
            ->add('webpage', 'url', array('label' => 'Webpage'))
            ->add('address', 'aspetos_admin_form_address_supplier')
            ->add(
                'cemeteries', 'entity', array(
                    'class'    => 'Model:Cemetery',
                    'choice_label' => 'name',
                    'multiple' => 'multiple',
                    'label'    => 'Cemeteries',
                    'attr'     => array('data-toggle' => 'multiple-select'),
                    'query_builder' => function (CemeteryRepository $er) {
                        $builder = $er->createQueryBuilder('s');
                        $builder->select('s', 'a')
                            // join, so we dont have 1+n query for the address we dont use anyway
                            ->join('s.address', 'a', Query\Expr\Join::LEFT_JOIN)
                            ->orderBy('s.name', 'ASC');

                        return $builder;
                    }
                )
            )
            ->add(
                'supplierTypes', 'entity', array(
                    'class'    => 'Model:SupplierType',
                    'choice_label' => 'name',
                    'multiple' => 'multiple',
                    'label'    => 'Supplier types',
                    'attr'     => array('data-toggle' => 'multiple-select'),
                    'query_builder' => function (SupplierTypeRepository $er) {
                        $result = $er->createQueryBuilder('st');
                        $result->orderBy('st.name', 'ASC');

                        return $result;
                    }
                )
            )

            ->add('save', 'submit', array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary' )));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'validation_groups'     => array('default'),
                'data_class'            => 'Aspetos\Model\Entity\Supplier',
                'cascade_validation'    => true
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_supplier_supplier';
    }
}
