<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Forms\Address;

use Aspetos\Model\Repository\DistrictRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class User Form
 *
 * @package Aspetos\Bundle\AdminBundle\Forms\Address
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_address_address")
 * @DI\Tag("form.type")
 */
abstract class AddressType extends AbstractType
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
            ->add('street', 'text', array('label' => 'Street'))
            ->add('street2', 'text', array('label' => 'Street 2'))
            ->add('zipcode', 'integer', array('label' => 'Zipcode'))
            ->add('country', 'country', array(
                'preferred_choices' => array('AT', 'DE'),
                'attr' => array(
                    'class' => 'country filter'
                )
            ))
            /**
            ->add('region', 'entity', array(
                    'label'         => 'Region',
                    'class'         => 'Model:Region',
                    'choice_label'  => 'name',
                    'group_by'      => 'country',
                    'placeholder'   => 'Select region',
                    'attr'          => array(
                        'class'         => 'optgroupfilter',
                        'data-filter-by' => 'country'
                    )
                )
            )
            **/
            ->add('district', 'entity', array(
                    'label'         => 'District',
                    'class'         => 'Model:District',
                    'choice_label'  => 'name',
                    'group_by'      => 'region.name',
                    'placeholder'   => 'Select district',
                    'attr'          => array(
                        'class'         => 'optgroupfilter',
                        'data-filter-by' => 'region'
                    ),
                    'query_builder' => function (DistrictRepository $repository){
                        $builder = $repository->createQueryBuilder('s');
                        $builder->select('s', 'r')
                            // join, so we dont have 1+n query
                            ->join('s.region', 'r', Join::LEFT_JOIN)
                            ->orderBy('s.name', 'ASC');

                        return $builder;
                    }
                )
            );

        return $builder;
    }
}
