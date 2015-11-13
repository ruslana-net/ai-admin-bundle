<?php

namespace Ai\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Admin\AdminInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Pix\SortableBehaviorBundle\Services\PositionHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Ai\AdminBundle\Entity\BeUser;


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
        if ( method_exists($positionHandler, 'setPositionField') ){
            $positionHandler->setPositionField(array('default' => 'position'));
        }

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

    protected function configureTabMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        $obj = ($childAdmin) ? $childAdmin : $this;

        $menu->addChild('Add new', array('uri' => $obj->generateUrl('create')));

        if ( $action != 'list' ) {
            $menu->addChild('Back to list', array('uri' => $obj->generateUrl('list')));
        }

        if ( $action == 'edit' && $obj->hasRoute('show') )
        {
            $menu->addChild('Show',
                array('uri' => $obj->generateUrl('show', array('id' => $obj->getRequest()->get('id'))))
            );
        }

        if ( $action == 'show' && $obj->hasRoute('edit') )
        {
            $menu->addChild('Edit',
                array('uri' => $obj->generateUrl('edit', array('id' => $obj->getRequest()->get('id'))))
            );
        }
    }

    /**
    * @return bool
    */
    public function hasAdminRole(){
        if ( $this->getCurrentUser()->hasRole(BeUser::ROLE_SUPER_ADMIN)
            || $this->getCurrentUser()->hasRole(BeUser::ROLE_ADMIN)
        ){
            return true;
        }

        return false;
    }

    /**
     * @return \Ai\AdminBundle\Entity\BeUser
     */
    protected function getCurrentUser(){
        return $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
    }
} 