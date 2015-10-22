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

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class User Form
 *
 * @package Aspetos\Bundle\AdminBundle\Forms\Address
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_address_mortician", parent="aspetos_admin_form_address_address")
 * @DI\Tag("form.type")
 */
class MorticianType extends AddressType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
            'validation_groups' => array('default'),
            'data_class' => 'Aspetos\Model\Entity\MorticianAddress',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_address_mortician';
    }
}
