<?php

namespace Ai\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;
use Pix\SortableBehaviorBundle\Services\PositionHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DefaultAdmin extends Admin {
    public $last_position = 0;

    protected $container;
    protected $positionService;

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'position',
    );

    protected $ExportFields=array();

    protected $translationDomain = 'messages';

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param PositionHandler $positionHandler
     */
    public function setPositionService(PositionHandler $positionHandler)
    {
        $positionHandler->setPositionField(array('default' => 'position'));
        $this->positionService = $positionHandler;
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('move', $this->getRouterIdParameter() . '/move/{position}');
    }

    /**
     * Export fields
     *
     * @return array
     */
    public function getExportFields() {
        $fields =  count($this->ExportFields) ? $this->ExportFields : parent::getExportFields();

        $expFields = array();
        foreach($fields as $field){
            $expFields[$this->trans( ucfirst($field), array(), 'messages' )]=$field;
        }

        return $expFields;
    }
} 