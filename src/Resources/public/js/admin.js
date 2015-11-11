jQuery('#sonata-ba-field-container-{{ id }}').on('sonata.add_element', function(){
    jQuery('#sonata-ba-field-container-{{ id }}').find('textarea.ckeditor').ckeditor();
    <!--jQuery('#sonata-ba-field-container-{{ id }}').find('input.slider_ui').slider();-->
    jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ "" ] );
    jQuery(".datepicker").datepicker( jQuery.datepicker.regional[ "ru" ]);
    jQuery(".datepicker").hide();
});