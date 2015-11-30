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

use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class User Form
 *
 * @package Aspetos\Bundle\AdminBundle\Forms\Permission
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_mortician_permission")
 * @DI\Tag("form.type")
 */
class PermissionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('permissions', 'switch_entity', array(
                'class' => 'Model:Permission',
                'property' => 'title',
                'expanded' => true,
                'multiple' => true,
                'attr'  => array(
                    //'align_with_widget' => true
                ),
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->where('s.name LIKE :permission')
                        ->orderBy('s.name', 'ASC')
                        ->setParameter('permission', 'mortician.%');
                }
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
            'data_class' => 'Aspetos\Model\Entity\BaseUser',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_mortician_permission';
    }
}
