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

use Aspetos\Model\Entity\Cemetery;
use Aspetos\Model\Entity\Mortician;
use Aspetos\Model\Entity\Obituary;
use Aspetos\Model\Entity\Supplier;
use Aspetos\Model\Repository\CemeteryRepository;
use Aspetos\Model\Repository\MorticianRepository;
use Aspetos\Model\Repository\SupplierRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Aspetos\Bundle\AdminBundle\Forms\Mortician\ObituaryType as MorticianObituaryType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * Class Mortician/Obituary Form
 *
 * @package Aspetos\Bundle\AdminBundle\Forms\Mortician
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos_admin_form_obituary")
 * @DI\Tag("form.type")
 */
class ObituaryType extends MorticianObituaryType
{
    /**
     * @var AuthorizationChecker
     */
    protected $authorizationChecker;

    /**
     * ObituaryType constructor.
     *
     * @param AuthorizationChecker $authorizationChecker
     *
     * @DI\InjectParams({
     *    "authorizationChecker" = @DI\Inject("security.authorization_checker")
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
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $builder->add(
                'mortician', 'entity', array(
                    'class'        => 'Model:Mortician',
                    'choice_label' => 'formattedName',
                    'label'        => 'Mortician',
                    'placeholder'  => 'Select mortician',
                    'empty_data'   => null,
                    'attr'         => array('class' => 'select2'),
                    'query_builder' => function (MorticianRepository $repository){
                        $builder = $repository->createQueryBuilder('m');
                        $builder->select('m', 'a')
                            // join, so we dont have 1+n query
                            ->join('m.address', 'a', Query\Expr\Join::LEFT_JOIN)
                            ->orderBy('m.name', 'ASC');

                        return $builder;
                    },
                )
            );
        }

        parent::buildForm($builder, $options);

        /**
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    $form = $event->getForm();

                }
            );
        }
         **/
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'validation_groups'     => array('default'),
                'data_class'            => 'Aspetos\Model\Entity\Obituary',
                'cascade_validation'    => true
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_obituary';
    }
}
