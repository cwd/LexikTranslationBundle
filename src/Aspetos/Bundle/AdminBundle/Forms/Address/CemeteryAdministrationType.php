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

use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CemeteryAdministration Form
 *
 * @package Aspetos\Bundle\AdminBundle\Forms\Address
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_address_cemetery_administration")
 * @DI\Tag("form.type")
 */
class CemeteryAdministrationType extends AddressType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return misc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = parent::buildForm($builder, $options);

        $builder
            ->add('phone', 'tel', array('label' => 'Phone'))
            ->add('fax', 'tel', array('label' => 'Fax'))
            ->add('email', 'email', array('label' => 'E-Mail'))
            ->add('webpage', 'url', array('label' => 'Webpage'));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
            'validation_groups' => array('default'),
            'data_class' => 'Aspetos\Model\Entity\CemeteryAdministration',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_address_cemetery_administration';
    }
}
