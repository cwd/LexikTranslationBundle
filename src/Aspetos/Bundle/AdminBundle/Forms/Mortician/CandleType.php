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

use Aspetos\Model\Repository\ProductRepository;
use Aspetos\Service\ProductService;
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
 * @DI\Service("aspetos_admin_form_mortician_candle")
 * @DI\Tag("form.type")
 */
class CandleType extends AbstractType
{
    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * CandleType constructor.
     *
     * @param ProductService $productService
     * @DI\InjectParams({
     *     "productService" = @DI\Inject("aspetos.service.product.product")
     * })
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $productService = $this->productService;
        $country = $options['data']->getObituary()->getCountry();

        $builder->add('content', 'textarea', array('label' => 'Content'))
                ->add('product', 'entity', array(
                    'label'         => 'Candle',
                    'class'         => 'Model:Product',
                    'choice_label'  => 'name',
                    'group_by'      => 'getCandleTypeText',
                    'placeholder'   => 'Select product',
                    'attr'          => array(
                        'class'         => 'select2',
                        'data-placeholder' => 'Select product'
                    ),
                    'query_builder' => function (ProductRepository $repository) use ($productService, $country) {
                        $category = $productService->getEm()->getReference('Model:ProductCategory', 54);

                        $builder = $repository->createQueryBuilder('p');
                        $builder->select('p')
                            ->leftJoin('p.productHasCategory', 'phc')
                            ->leftJoin('p.productAvailability', 'pa')
                            ->where('phc.productCategory = :category')
                            ->andWhere('p.state = 1')
                            ->andWhere('pa.state = 1')
                            ->andWhere('pa.country = :country')
                            ->orderBy('p.name', 'ASC')
                            ->setParameter('category', $category)
                            ->setParameter('country', $country);

                        return $builder;
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
            'data_class' => 'Aspetos\Model\Entity\Candle',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_form_mortician_candle';
    }
}
