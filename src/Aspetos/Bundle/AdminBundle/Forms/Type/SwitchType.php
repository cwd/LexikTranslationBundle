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
     *
     * @DI\InjectParams({
     *  "translator" = @DI\Inject("translator", strict = false)
     * })
     */
    public function __construct(DataCollectorTranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param FormFactoryInterface $factory
     * @param String               $name
     * @param array                $options
     * /
    public function createBuilder(FormFactoryInterface $factory, $name, array $options = array())
    {
        if (isset($options['attr'])) {
            $defaultAttributes = $this->getDefaultAttributes();
            foreach ($defaultAttributes as $key => $value) {
                if (!isset($options['attr'][$key])) {
                    $options['attr'][$key] = $value;
                }
            }
        }

        dump($options);
        return parent::createBuilder($factory, $name, $options);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     * /
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['attr'])) {
            $defaultAttributes = $this->getDefaultAttributes();
            foreach ($defaultAttributes as $key => $value) {
                if (!isset($options['attr'][$key])) {
                    $options['attr'][$key] = $value;
                }
            }
        }

        dump($options);
        return parent::buildForm($builder, $options);
    }

    /**
     * @param OptionsResolver $optionsResolver
     */
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults(array(
            'attr' => $this->getDefaultAttributes()
        ));
    }

    /**
     * @return array
     */
    protected function getDefaultAttributes()
    {
        return array(
            'data-on-text' => $this->translator->trans('On'),
            'data-off-text' => $this->translator->trans('Off'),
            'data-size' => 'large',
            'data-on-color' => 'success',
            'data-off-color' => 'danger',
            'class' => 'make-switch',
            'align_with_widget' => true
        );
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