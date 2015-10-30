<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Forms\Type;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\DataCollectorTranslator;

/**
 * Class SwitchType
 *
 * @package Aspetos\Bundle\AdminBundle\Forms\Type
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 */
class SwitchType extends CheckboxType
{
    /**
     * @var DataCollectorTranslator
     */
    protected $translator;

    /**
     * @param DataCollectorTranslator $translator
     */
    public function __construct(DataCollectorTranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // this has to be called because otherwise, we have two BooleanToStringTransformer which results in 'Unable to transform value for property path "FIELD": Expected a Boolean'
        $builder->resetViewTransformers();
        parent::buildForm($builder, $options);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('attr', array(
            'data-on-text' => $this->translator->trans('On'),
            'data-off-text' => $this->translator->trans('Off'),
            'data-size' => 'small',
            'data-on-color' => 'success',
            'data-off-color' => 'danger',
            'class' => 'make-switch',
            'align_with_widget' => true
        ));
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'checkbox';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'switch';
    }
}
