<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\LegacyBundle\Forms;

use Cwd\GenericBundle\Doctrine\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class Obituary Form
 *
 * @package Aspetos\Bundle\LegacyBundle\Forms
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_legacy_form_obituary")
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
        $builder
            ->add('name', 'text', array('label' => 'Name'))
            ->add('save', 'submit', array('label' => 'Save'));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('default'),
            'data_class' => 'Aspetos\Model\Entity\Obituary',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_legacy_form_obituary';
    }
}
