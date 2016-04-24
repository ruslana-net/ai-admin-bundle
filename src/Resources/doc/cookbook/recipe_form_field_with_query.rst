Admin form field with query
===========================


1. Add to admin class
----------------------

.. code-block:: php

    # Admin/CategoryAdmin.php
    
    # ...
    public function configureFormFields(FormMapper $formMapper)
    {
        $em = $this->modelManager->getEntityManager('Ai\ConsultationBundle\Entity\Category');

        $query = $em->createQueryBuilder();
        $query
            ->select('c')
            ->from('Ai\ConsultationBundle\Entity\Category', 'c')
            ->where(
                $query->expr()->isNull('c.parent')
            )
            ->orderBy('c.position', 'ASC');

        $formMapper
            ->with('Default')
            ->add('parent', null, [
                'required' => true,
                'query_builder' => $query
            ])
            ->end();

        parent::configureFormFields($formMapper);
    }