<?php

namespace Ai\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\FormBuilderInterface;
use Ai\AdminBundle\Services\ImageManager;
use Ai\AdminBundle\Form\DataTransformer\ImageToStringTransformer;

/**
 * ImageUploaderType
 *
 * @author Ruslan Muriev
 */
class ImageUploaderType extends AbstractType
{
    private $imageManager;

    private $formOptions;

    public function __construct(ImageManager $imageManager, array $formOptions)
    {
        $this->formOptions = $formOptions;
        $this->imageManager = $imageManager;
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
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new ImageToStringTransformer($this->imageManager, $options));
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