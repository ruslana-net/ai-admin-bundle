<?php

namespace Ai\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * JquerySliderType
 *
 * @author Ruslan Muriev
 */
class JquerySliderType extends AbstractType
{
    private $sliderOptions;

    public function __construct(array $sliderOptions)
    {
        $this->sliderOptions = $sliderOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['sliderOptions'] = $this->sliderOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'integer';
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ai_jqueryslider';
    }
}