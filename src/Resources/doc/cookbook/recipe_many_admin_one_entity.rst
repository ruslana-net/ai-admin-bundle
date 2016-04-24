Add many admin classes for one entity class
============================================

1. Create some admin classes with different $baseRouteName
----------------------------------------------------------

.. code-block:: php

    # Admin/SectionAdmin.php

    # ...
    class SectionAdmin extends DefaultAdmin
    {
        protected $baseRouteName = 'section';
        protected $baseRoutePattern = 'consultant/section';

        /**
         * @param string $context
         * @return QueryBuilder
         */
        public function createQuery($context = 'list')
        {
            /** @var QueryBuilder $query **/
            $query = parent::createQuery($context);

            $query
                ->andWhere(
                    $query->expr()->isNull($query->getRootAliases()[0] . '.parent')
                );

            return $query;
        }
    }

    # Admin/CategoryAdmin.php

    # ...
    class CategoryAdmin extends DefaultAdmin
    {
        protected $baseRouteName = 'category';
        protected $baseRoutePattern = 'consultant/category';

        /**
         * @param string $context
         * @return QueryBuilder
         */
        public function createQuery($context = 'list')
        {
            /** @var QueryBuilder $query **/
            $query = parent::createQuery($context);

            $query
                ->andWhere(
                    $query->expr()->isNotNull($query->getRootAliases()[0] . '.parent')
                );

            return $query;
        }
    }

2. Add to service.yml
---------------------

.. code-block:: yaml

    # app/config/services.yml

    # ...
    ai_consultation.admin.section:
        class: Ai\ConsultationBundle\Admin\SectionAdmin
        arguments: [~, Ai\ConsultationBundle\Entity\Category, AiAdminBundle:Admin]
        tags:
            -
                name: sonata.admin
                manager_type: orm
                group: consultation
                label: breadcrumb.link_section_list
                label_translator_strategy: sonata.admin.label.strategy.underscore
                autoadmin: true
        calls:
            - [ setContainer, [ "@service_container" ] ]
            - [ setAdminIcon, [ '<i class="glyphicon glyphicon-share"></i>' ] ]

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
            - [ setAdminIcon, [ '<i class="glyphicon glyphicon-tasks"></i>' ] ]