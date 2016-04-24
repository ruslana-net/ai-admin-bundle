<?php

namespace Ai\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Exception\InvalidArgumentException;

/**
 * ImageUploaderType
 *
 * @author Ruslan Muriev
 */
class ImageUploaderType extends AbstractType
{
    private $formOptions;

    public function __construct(array $formOptions)
    {
        $this->formOptions = $formOptions;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'oneup_uploader_id' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if($options['oneup_uploader_id'] === null)
        {
            throw new InvalidArgumentException('Option oneup_uploader_id is required');
        }

        $view->vars['formOptions'] = $this->formOptions;
        $view->vars['options'] = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ai_admin_image_uploader';
    }
}