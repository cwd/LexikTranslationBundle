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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BookEntry Form
 *
 * @package Aspetos\Bundle\LegacyBundle\Forms
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_legacy_form_condolence")
 * @DI\Tag("form.type")
 */
class CondolenceType extends AbstractType
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
            ->add('body', 'textarea', array(
                'label' => 'Body',
                'attr' => array(
                    'rows' => 10
                )
            ))
            ->add('hide', 'choice', array(
                'choices' => array(0 => 'Show', 1 => 'Hide'),
                'label'   => 'Visability',
                'required' => true
            ))
            ->add('save', 'submit', array('label' => 'Save'));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('default'),
            'data_class' => 'Aspetos\Bundle\LegacyBundle\Model\Entity\BookEntry',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_legacy_form_condolence';
    }
}
