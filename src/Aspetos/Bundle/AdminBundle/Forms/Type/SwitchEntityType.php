<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Forms\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SwitchEntityType
 *
 * @package Aspetos\AdminBundle\Form\Type
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class SwitchEntityType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('dataType', $options['dataType']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'required' => false,
            'dataType' => 'entity'
        ));
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function getAllowedOptionValues(array $options)
    {
        return array('required' => array(false));
    }

    /**
     *
     * @return string
     */
    public function getParent()
    {
        return 'entity';
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'switch_entity';
    }

}
