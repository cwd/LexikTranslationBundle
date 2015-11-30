<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Forms;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class User Form
 *
 * @package Aspetos\Bundle\AdminBundle\Forms\Mortician
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_condolence")
 * @DI\Tag("form.type")
 */
class CondolenceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fromName', 'text', array('label' => 'From'))
                ->add('content', 'textarea', array('label' => 'Content'))
                ->add(
                    'public', 'checkbox', array(
                        'label' => 'Public',
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
                ->add('state', 'choice', array(
                    'choices' => array(
                        'new'             => 'New',
                        'active'          => 'Active',
                        'inactive'        => 'Inactive',
                    ),
                    'attr' => array(
                        'label' => 'State'
                    )
                ));



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
                if ($data->getId() === null) {
                    return array('default', 'create');
                }

                return array('default');
            },
            'data_class' => 'Aspetos\Model\Entity\Condolence',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_condolence';
    }
}
