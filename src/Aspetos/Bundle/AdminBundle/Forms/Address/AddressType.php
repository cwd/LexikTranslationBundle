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
            ->add(
                'region', 'entity', array(
                'label'         => 'Region',
                'class'         => 'Model:Region',
                'choice_label'  => 'name'
                )
            )
            ->add('country', 'country', array(
                'preferred_choices' => array('AT', 'DE')
            ));

        return $builder;
    }
}
