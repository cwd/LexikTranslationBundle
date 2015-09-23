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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * Class User Form
 *
 * @package Aspetos\Bundle\AdminBundle\Forms
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_user")
 * @DI\Tag("form.type")
 */
class UserType extends AbstractType
{
    /**
     * @var AuthorizationChecker
     */
    public $authorizationChecker;

    /**
     * @param AuthorizationChecker $authorizationChecker
     *
     * @DI\InjectParams({
     *      "authorizationChecker" = @DI\Inject("security.authorization_checker"),
     * })
     */
    public function __construct(AuthorizationChecker $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return misc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', 'text', array('label' => 'Firstname'))
            ->add('lastname', 'text', array('label' => 'Lastname'))
            ->add('email', 'text', array('label' => 'Email'))
            ->add('password', 'repeated', [
                    'type'  => 'password',
                    'label' => 'Password',
                    'invalid_message' => 'Password fields must match',
                    'first_options' => ['label' => 'Password'],
                    'second_options' => ['label' => 'Repeat Password']
                ]
            );

        if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $builder
                ->add('userRoles', 'entity', array(
                    'class'    => 'Model:Role',
                    'choice_label' => 'name',
                    'multiple' => 'multiple',
                    'label'    => 'Roles',
                    'attr'     => array('class' => 'select2me'),
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er)
                    {
                        $result = $er->createQueryBuilder('o');
                        $result->orderBy('o.role', 'ASC');

                        return $result;
                    }
                ))
                ->add('enabled', 'checkbox', array(
                    'label' => false,
                    'attr' => array(
                        'data-toggle' => 'toggle',
                        'data-on' => 'active',
                        'data-off' => 'inactive',
                        'data-onstyle' => 'btn green',
                        'data-offstyle' => 'danger',
                        'class' => 'switcher',
                        'align_with_widget' => 'true'
                    )
                ));
        }

        $builder
            ->add('save', 'submit', array('label' => 'Save'));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('default'),
            'data_class' => 'Aspetos\Model\Entity\Admin',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_user';
    }
}
