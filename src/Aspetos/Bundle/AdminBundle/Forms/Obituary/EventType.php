<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Forms\Obituary;

use Aspetos\Model\Repository\ObituaryEventTypeRepository;
use Aspetos\Model\Repository\ProductCategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Obituary/Event Form
 *
 * @package Aspetos\Bundle\AdminBundle\Forms\Address
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_obituary_event")
 * @DI\Tag("form.type")
 */
class EventType extends AbstractType
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
            ->add('obituaryEventType', 'entity', array(
                    'label'         => 'Type',
                    'class'         => 'Model:ObituaryEventType',
                    'choice_label'  => 'name',
                    'placeholder'   => 'Select type',
                    'query_builder' => function (ObituaryEventTypeRepository $repository){
                        $builder = $repository->createQueryBuilder('t');
                        $builder->orderBy('t.name', 'ASC');

                        return $builder;
                    }
                )
            )
            ->add('dateStart', 'datetime', array(
                'label' => 'Start date'
            ))
            ->add('description', 'textarea', array('label' => 'Description'));

        return $builder;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'validation_groups' => array('default'),
                'data_class' => 'Aspetos\Model\Entity\ObituaryEvent',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_obituary_event';
    }
}
