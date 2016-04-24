Add children
============


1. Add self children
--------------------

Add to services.yml

.. code-block:: yaml

    # app/config/services.yml
    
    # ...
    ai_consultation.admin.category:
        class: Ai\ConsultationBundle\Admin\CategoryAdmin
        arguments: [~, Ai\ConsultationBundle\Entity\Category, AiAdminBundle:Admin]
        tags:
            -
                name: sonata.admin
                manager_type: orm
                group: consultation
                label: breadcrumb.link_category_list
                label_translator_strategy: sonata.admin.label.strategy.underscore
                autoadmin: true
        calls:
            - [ setContainer, [ "@service_container" ] ]
            - [ addChild, ['@ai_consultation.admin.category.child']]
            - [ setAdminIcon, [ '<i class="glyphicon glyphicon-share"></i>' ] ]

    ai_consultation.admin.category.child:
        class: Ai\ConsultationBundle\Admin\CategoryChildAdmin
        arguments: [~, Ai\ConsultationBundle\Entity\Category, AiAdminBundle:Admin]
        tags:
            -
                name: sonata.admin
                manager_type: orm
                group: consultation
                label: breadcrumb.link_category_list
                label_translator_strategy: sonata.admin.label.strategy.underscore
                autoadmin: true
        calls:
            - [ setContainer, [ "@service_container" ] ]
            - [ setAdminIcon, [ '<i class="glyphicon glyphicon-share"></i>' ] ]

Add to entity class

.. code-block:: php

    use Ai\AdminBundle\Model\OneToManySelf;

    class Category
    {
        use //...
            OneToManySelf
        ;

Then create the admin class copy and put it $baseRouteName

.. code-block:: php

    class CategoryAdmin extends DefaultAdmin
    {
        protected $baseRouteName = 'category';
        protected $baseRoutePattern = 'consultant/category';

The admin class copy:

.. code-block:: php

    class CategoryChildAdmin extends DefaultAdmin
    {


2. Add another children
-----------------------

Add to service.yml

.. code-block:: yaml

    # app/config/services.yml

    # ...
    ai_consultation.admin.category:
        //...
        calls:
            - [ addChild, ['@ai_consultation.admin.theme']]

    ai_consultation.admin.theme:
        //...
        calls:
            - [ setAdminIcon, [ '<i class="glyphicon glyphicon-list"></i>' ] ] #child menu icon

