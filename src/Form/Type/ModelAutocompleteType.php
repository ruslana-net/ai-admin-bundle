<?php

namespace Ai\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ai\AdminBundle\Form\DataTransformer\ModelToIdPropertyTransformer;

/**
 * JquerySliderType
 *
 * @author Ruslan Muriev
 */
class ModelAutocompleteType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ModelManagerInterface $modelManager
     */
    public function __construct(ObjectManager $om){
        $this->om = $om;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new ModelToIdPropertyTransformer($options['om'], $options['class'], $options['property'], $options['multiple'], $options['to_string_callback'], $options['create']), true);

        $builder->add('title', 'text', array('attr'=>$options['attr'], 'property_path' => '[labels][0]'));
        $builder->add('identifiers', 'collection', array('type'=>'hidden', 'allow_add' => true, 'allow_delete' => true));

        $builder->setAttribute('property', $options['property']);
        $builder->setAttribute('callback', $options['callback']);
        $builder->setAttribute('minimum_input_length', $options['minimum_input_length']);
        $builder->setAttribute('items_per_page', $options['items_per_page']);
        $builder->setAttribute('req_param_name_page_number', $options['req_param_name_page_number']);
        $builder->setAttribute('disabled', $options['disabled'] || $options['read_only']);
        $builder->setAttribute('to_string_callback', $options['to_string_callback']);
        $builder->setAttribute('maximumSelectionSize', $options['maximumSelectionSize']);
        $builder->setAttribute('create', $options['create']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['placeholder'] = $options['placeholder'];
        $view->vars['multiple'] = $options['multiple'];
        $view->vars['minimum_input_length'] = $options['minimum_input_length'];
        $view->vars['items_per_page'] = $options['items_per_page'];

        // ajax parameters
        $view->vars['url'] = $options['url'];
        $view->vars['route'] = $options['route'];
        $view->vars['req_params'] = $options['req_params'];
        $view->vars['req_param_name_search'] = $options['req_param_name_search'];
        $view->vars['req_param_name_page_number'] = $options['req_param_name_page_number'];
        $view->vars['req_param_name_items_per_page'] = $options['req_param_name_items_per_page'];
        $view->vars['maximumSelectionSize'] = $options['maximumSelectionSize'];
        $view->vars['create'] = (int) $options['create'];

        // dropdown list css class
        $view->vars['dropdown_css_class'] = $options['dropdown_css_class'];
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'om'                              => $this->om,
            'attr'                            => array(),
            'compound'                        => true,
            'class'                           => null,
            'callback'                        => null,
            'multiple'                        => false,

            'placeholder'                     => '',
            'minimum_input_length'            => 3, //minimum 3 chars should be typed to load ajax data
            'items_per_page'                  => 10, //number of items per page

            'to_string_callback'              => null,

            // ajax parameters
            'url'                             => '',
            'route'                           => array('name'=>'ai_admin_retrieve_autocomplete_items', 'parameters'=>array()),
            'req_params'                      => array(),
            'req_param_name_search'           => 'q',
            'req_param_name_page_number'      => '_page',
            'req_param_name_items_per_page'   => '_per_page',

            // dropdown list css class
            'dropdown_css_class'              => 'sonata-autocomplete-dropdown',
            'maximumSelectionSize'            => 5,
            'create'                      => false,
        ));

        $resolver->setRequired(array('class', 'property'));
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ai_admin_model_autocomplete';
    }
}