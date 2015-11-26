<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Forms\Mortician;

use Aspetos\Model\Entity\Cemetery;
use Aspetos\Model\Entity\Mortician;
use Aspetos\Model\Entity\Obituary;
use Aspetos\Model\Entity\Supplier;
use Aspetos\Model\Repository\CemeteryRepository;
use Aspetos\Model\Repository\MorticianRepository;
use Aspetos\Model\Repository\SupplierRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Mortician/Obituary Form
 *
 * @package Aspetos\Bundle\AdminBundle\Forms\Mortician
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_mortician_obituary")
 * @DI\Tag("form.type")
 */
class ObituaryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return misc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $mortician = $options['data']->getMortician();

        $builder
            ->add('gender', 'choice', array(
                'label' => 'Gender',
                'choices' => array(
                    Obituary::GENDER_FEMALE => 'Female',
                    Obituary::GENDER_MALE   => 'Male',
                    Obituary::GENDER_UNDEF  => 'Unknown'
                )
            ))
            ->add('titlePrefix', 'text', array('label' => 'Title pre'))
            ->add('firstname', 'text', array('label' => 'Firstname'))
            ->add('lastname', 'text', array('label' => 'Lastname'))
            ->add('titlePostfix', 'text', array('label' => 'Title post'))
            ->add('bornAs', 'text', array('label' => 'Born as'))
            ->add('dayOfBirth', 'date', array(
                'label' => 'Day of birth',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => array(
                    'placeholder' => 'jjjj-mm-tt',
                    'class' => 'date-picker',
                    'data-provide' => 'datepicker',
                ),
            ))
            ->add('dayOfDeath', 'date', array(
                'label' => 'Day of death',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => array(
                    'placeholder' => 'jjjj-mm-tt',
                    'class' => 'date-picker',
                    'data-provide' => 'datepicker',
                ),
            ))
            ->add('cemetery', 'entity', array(
                'label'         => 'Cemetery',
                'class'         => 'Model:Cemetery',
                'choice_label'  => 'formatedName',
                'group_by'      => 'address.region.name',
                'placeholder'   => 'Select cemetery',
                'attr'          => array(
                    'class'         => 'select2',
                    'data-placeholder' => 'Select cemetery'
                ),
                'query_builder' => function (CemeteryRepository $repository){
                    $builder = $repository->createQueryBuilder('c');
                    $builder->select('c', 'a', 'm', 'r')
                        // join, so we dont have 1+n query
                        ->leftJoin('c.address', 'a')
                        ->leftJoin('a.region', 'r')
                        ->leftJoin('c.morticians', 'm')
                        ->orderBy('c.name', 'ASC');

                    return $builder;
                },
                'preferred_choices' => function (Cemetery $cemetery) use ($mortician) {
                    return $cemetery->getMorticians()->contains($mortician);
                }
            ))
            ->add('suppliers', 'entity', array(
                'label'         => 'Suppliers',
                'class'         => 'Model:Supplier',
                'choice_label'  => 'formatedName',
                'group_by'      => 'address.region.name',
                'placeholder'   => 'Select suppliers',
                'multiple'      => true,
                'attr'          => array(
                    'data-toggle' => 'multiple-select',
                ),
                'query_builder' => function (SupplierRepository $repository) use ($mortician) {
                    $builder = $repository->createQueryBuilder('s');
                    $builder->select('s', 'a', 'm', 'r')
                        // join, so we dont have 1+n query
                        ->join('s.address', 'a')
                        ->join('a.region', 'r')
                        ->join('s.morticians', 'm')
                        ->where('m = :mortician')
                        ->setParameter('mortician', $mortician)
                        ->orderBy('s.name', 'ASC');

                    return $builder;
                },
            ))
            ->add(
                'hide', 'checkbox', array(
                    'label' => 'Hide',
                    'attr' => array(
                        'data-on-text' => '<i class="fa fa-eye"></i>',
                        'data-off-text' => '<i class="fa fa-eye-slash"></i>',
                        'data-size' => 'large',
                        'data-on-color' => 'success',
                        'data-off-color' => 'danger',
                        'class' => 'make-switch',
                        'align_with_widget' => true
                    )
                )
            )
            ->add(
                'allowCondolence', 'checkbox', array(
                    'label' => 'Allow condolences',
                    'attr' => array(
                        'data-on-text' => '<i class="fa fa-check-square"></i>',
                        'data-off-text' => '<i class="fa fa-minus-square"></i>',
                        'data-size' => 'large',
                        'data-on-color' => 'success',
                        'data-off-color' => 'danger',
                        'class' => 'make-switch',
                        'align_with_widget' => true
                    )
                )
            )
            ->add(
                'showOnlyBirthYear', 'checkbox', array(
                    'label' => 'Show only birth year',
                    'attr' => array(
                        'data-on-text' => '<i class="fa fa-check-square"></i>',
                        'data-off-text' => '<i class="fa fa-minus-square"></i>',
                        'data-size' => 'large',
                        'data-on-color' => 'success',
                        'data-off-color' => 'danger',
                        'class' => 'make-switch',
                        'align_with_widget' => true
                    )
                )
            )
            ->add('obituary', 'cwd_image_type', array('label' => 'Obituary'))
            ->add('portrait', 'cwd_image_type', array('label' => 'Portrait'))

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
                'data_class'            => 'Aspetos\Model\Entity\Obituary',
                'cascade_validation'    => true
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_mortician_obituary';
    }
}
