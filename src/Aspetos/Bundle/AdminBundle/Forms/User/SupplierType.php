<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Forms\User;

use Aspetos\Model\Repository\SupplierAddressRepository;
use Aspetos\Model\Repository\SupplierRepository;
use Doctrine\ORM\Query\Expr\Join;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class User Form
 *
 * @package Aspetos\Bundle\AdminBundle\Forms\User
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_user_supplier", parent="aspetos_admin_form_user_user")
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
        $builder->add('user', 'aspetos_admin_form_user_user')
                ->add(
                    'supplier', 'entity', array(
                        'class'        => 'Model:Supplier',
                        'choice_label' => 'name',
                        'label'        => 'Supplier',
                        'placeholder'  => 'Select supplier',
                        'empty_data'   => null,
                        'attr'         => array('class' => 'select2me'),
                        'query_builder' => function (SupplierRepository $repository){
                            $builder = $repository->createQueryBuilder('s');
                            $builder->select('s', 'a')
                                // join, so we dont have 1+n query
                                ->join('s.address', 'a', Join::LEFT_JOIN)
                                ->orderBy('s.name', 'ASC');

                            return $builder;
                        },
                    )
        );

        $builder
            ->add('save', 'submit', array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary' )));
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
            'validation_groups' => function (FormInterface $form) {
                $data = $form->getData();
                if ($data->getId() == null) {
                    return array('default', 'create');
                }

                return array('default');
            },
            'data_class' => 'Aspetos\Model\Entity\SupplierUser',
            'cascade_validation' => true,
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_user_supplier';
    }
}
