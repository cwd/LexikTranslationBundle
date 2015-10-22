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

use Aspetos\Model\Entity\Mortician;
use Aspetos\Model\Repository\MorticianRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Mortician Form
 *
 * @package Aspetos\Bundle\AdminBundle\Forms\Mortician
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_mortician_mortician")
 * @DI\Tag("form.type")
 */
class MorticianType extends AbstractType
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
            ->add('shortName', 'text', array('label' => 'Short Name'))
            ->add('description', 'textarea', array('label' => 'Description'))
            ->add(
                'parentMortician',
                'entity',
                array(
                    'class'        => 'Model:Mortician',
                    'choice_label' => 'formatedName',
                    'label'        => 'Parent Mortician',
                    'placeholder'  => '',
                    'empty_data'   => null,
                    'attr'         => array(
                        'class' => 'select2',
                        'data-placeholder' => 'Select parent mortician'
                    ),
                    'query_builder' => function (MorticianRepository $repository){
                        $builder = $repository->createQueryBuilder('m');
                        $builder->select('m', 'a')
                                // join, so we dont have 1+n query for the address we dont use anyway
                                ->join('m.address', 'a', Query\Expr\Join::LEFT_JOIN)
                                ->orderBy('m.name', 'ASC');

                        return $builder;
                    }
                )
            )
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
            ->add('vat', 'text', array('label' => 'Vat'))
            ->add('commercialRegNumber', 'text', array('label' => 'Commercial Register Number'))
            ->add('country', 'country', array(
                'label' => 'Country',
                'preferred_choices' => array('AT', 'DE')
            ))
            ->add('contactName', 'text', array('label' => 'Contact Name'))
            //->add('logo', 'aspetos_admin_form_media')
            //->add('avatar', 'aspetos_admin_form_media')
            ->add('state', 'switch', array('label' => 'State'))
            ->add('partnerVienna', 'switch', array('label' => 'Partner Vienna'))
            ->add('address', 'aspetos_admin_form_address_mortician')
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
                'data_class'            => 'Aspetos\Model\Entity\Mortician',
                'cascade_validation'    => true
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_mortician_mortician';
    }
}
