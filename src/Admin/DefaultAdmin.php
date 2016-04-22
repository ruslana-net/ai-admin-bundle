<?php

namespace Ai\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
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

    protected $configureListWithourActions=false;

    protected static $classTraites=[];

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('id');

        if($this->hasTrait('Ai\AdminBundle\Model\NameTrait')){
            $datagridMapper->add('name');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\DescrTrait')){
            $datagridMapper->add('descr');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\ContentTrait')){
            $datagridMapper->add('content');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\CommentTrait')){
            $datagridMapper->add('comment');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\CrdateTrait')){
            $datagridMapper->add('crdate', 'doctrine_orm_date_range', array(), 'sonata_type_date_range',
                array(
                    'format' => 'dd-MM-yyyy',
                    'widget' => 'single_text',
                    'attr' => array('class' => 'datepicker'))
            );
        }
        if($this->hasTrait('Ai\AdminBundle\Model\TstampTrait')){
            $datagridMapper->add('tstamp', 'doctrine_orm_date_range', array(), 'sonata_type_date_range',
                array(
                    'format' => 'dd-MM-yyyy',
                    'widget' => 'single_text',
                    'attr' => array('class' => 'datepicker'))
            );
        }

        if($this->hasTrait('Ai\AdminBundle\Model\EnabledTrait')){
            $datagridMapper->add('enabled');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\PaidTrait')){
            $datagridMapper->add('paid');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\CeoTrait')){
            $datagridMapper
                ->add('ceoTitle')
                ->add('ceoKeywords')
                ->add('ceoDescription')
            ;
        }
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->with('Default');

        if($this->hasTrait('Ai\AdminBundle\Model\TitleTrait')){
            $formMapper->add('title');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\NameTrait')){
            $formMapper->add('name');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\DescrTrait')){
            $formMapper->add('descr', null, array('attr'=>array('class'=>'ckeditor')));
        }

        if($this->hasTrait('Ai\AdminBundle\Model\ContentTrait')){
            $formMapper->add('content', null, array('attr'=>array('class'=>'ckeditor')));
        }

        if($this->hasTrait('Ai\AdminBundle\Model\CommentTrait')){
            $formMapper->add('comment');
        }

        if($this->hasTrait('Ai\CmsCoreBundle\Model\FilesTrait')){
            //TODO
        }

        if($this->hasTrait('Ai\CmsCoreBundle\Model\ImagesTrait')){
            //TODO
        }

        if($this->hasTrait('Ai\AdminBundle\Model\FileTrait')){
            //TODO
        }

        if($this->hasTrait('Ai\AdminBundle\Model\EnabledTrait')){
            $formMapper->add('enabled');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\PaidTrait')){
            $formMapper->add('paid');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\OnlyRegisteredTrait')){
            $formMapper->add('onlyRegistered');
        }

        $formMapper->end();

        if($this->hasTrait('Ai\AdminBundle\Model\CeoTrait')){
            $formMapper
                ->with('CEO')
                ->add('ceoTitle')
                ->add('ceoKeywords')
                ->add('ceoDescription')
                ->end()
            ;
        }

        if($this->hasTrait('Ai\AdminBundle\Model\NameSlugTrait')){
            $formMapper->with('CEO')->add('slug')->end();
        }

        if($this->hasTrait('Ai\AdminBundle\Model\TitleSlugTrait')){
            $formMapper->with('CEO')->add('slug')->end();
        }

        if($this->hasTrait('Ai\AdminBundle\Model\CacheTrait')){
            $formMapper
                ->with('Other')
                ->add('noCache')
                ->add('cacheTimeout')
                ->end();
        }
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('id');

        if($this->hasTrait('Ai\AdminBundle\Model\NameTrait')){
            $listMapper->add('name');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\DescrTrait')){
            $listMapper->add('descr');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\EnabledTrait')){
            $listMapper->add('enabled', null, ['editable' => true]);
        }

        if($this->hasTrait('Ai\AdminBundle\Model\PaidTrait')){
            $listMapper->add('paid', null, ['editable' => true]);
        }
        
        if($this->configureListWithourActions === false){
            self::configureListAction($listMapper);
        }
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListAction(ListMapper $listMapper)
    {
        if(!$listMapper->has('_action')) {
            $listMapper
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
                        'delete' => array(),
                        'move' => array(
                            'template' => 'PixSortableBehaviorBundle:Default:_sort.html.twig'
                        ),
                    )
                ));
        }
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {

        $showMapper->with('Default');

        $showMapper->add('id');

        if($this->hasTrait('Ai\AdminBundle\Model\TitleTrait')){
            $showMapper->add('title');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\NameTrait')){
            $showMapper->add('name');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\DescrTrait')){
            $showMapper->add('descr', null, array('attr'=>array('class'=>'ckeditor')));
        }

        if($this->hasTrait('Ai\AdminBundle\Model\ContentTrait')){
            $showMapper->add('content', null, array('attr'=>array('class'=>'ckeditor')));
        }

        if($this->hasTrait('Ai\AdminBundle\Model\CommentTrait')){
            $showMapper->add('comment');
        }

        if($this->hasTrait('Ai\CmsCoreBundle\Model\FilesTrait')){
            //TODO
        }

        if($this->hasTrait('Ai\CmsCoreBundle\Model\ImagesTrait')){
            //TODO
        }

        if($this->hasTrait('Ai\AdminBundle\Model\FileTrait')){
            //TODO
        }

        if($this->hasTrait('Ai\AdminBundle\Model\EnabledTrait')){
            $showMapper->add('enabled');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\PaidTrait')){
            $showMapper->add('paid');
        }

        if($this->hasTrait('Ai\AdminBundle\Model\OnlyRegisteredTrait')){
            $showMapper->add('onlyRegistered');
        }

        $showMapper->end();

        if($this->hasTrait('Ai\AdminBundle\Model\CeoTrait')){
            $showMapper
                ->with('CEO')
                ->add('ceoTitle')
                ->add('ceoKeywords')
                ->add('ceoDescription')
                ->end()
            ;
        }

        if($this->hasTrait('Ai\AdminBundle\Model\NameSlugTrait')){
            $showMapper->with('CEO')->add('slug')->end();
        }

        if($this->hasTrait('Ai\AdminBundle\Model\TitleSlugTrait')){
            $showMapper->with('CEO')->add('slug')->end();
        }

        if($this->hasTrait('Ai\AdminBundle\Model\CacheTrait')){
            $showMapper
                ->with('Other')
                ->add('noCache')
                ->add('cacheTimeout')
                ->add('crdate', 'datetime', array(
                    'label' => 'Дата создания',
                    'read_only' => true,
                    'widget' => 'single_text',
                    'format' => 'd.m.Y H:m',
                    'required' => false,
                ))
                ->add('tstamp', 'datetime', array(
                    'label' => 'Дата обновления',
                    'read_only' => true,
                    'widget' => 'single_text',
                    'format' => 'd.m.Y H:m',
                    'required' => false,
                ))
                ->end();
        }
    }

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

        if($obj->hasRoute('create')) {
            $menu->addChild('Add new', array('uri' => $obj->generateUrl('create')));
        }

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

    /**
     * @return array
     */
    public function getFileOptions($fieldOptions=array())
    {
        if($object = $this->getSubject())
        {
            if(file_exists($object->getAbsolutePath())){
                // RedirectResponse object
                $imagemanagerResponse = $this->container
                    ->get('liip_imagine.controller')
                    ->filterAction(
                        $this->request,         // http request
                        $object->getWebPath(),      // original image you want to apply a filter to
                        'admin_thumb'              // filter defined in config.yml
                    );

                // string to put directly in the "src" of the tag <img>
                $cacheManager = $this->container->get('liip_imagine.cache.manager');
                $srcPath = $cacheManager->getBrowserPath($object->getWebPath(), 'admin_thumb');

                $fieldOptions['help'] = '<img src="'.$srcPath.'" />';
            }
        }

        return $fieldOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function getBatchActions()
    {
        $actions = array();

        if ($this->hasRoute('delete') && $this->isGranted('DELETE')) {
            $actions['delete'] = array(
                'label'            => $this->trans('Delete', array(), $this->getTranslationDomain()),
                'ask_confirmation' => true, // by default always true
            );
        }

        return $actions;
    }

    /**
     * Check entity trait
     *
     * @param $traitClass
     * @return mixed
     */
    public function hasTrait($traitClass)
    {
        return array_key_exists($traitClass, $this->getClassUses());
    }

    /**
     * Get entity traites
     *
     * @return array
     */
    public function getClassUses()
    {
        if(!empty(self::$classTraites)){
            return self::$classTraites;
        }

        return self::$classTraites = class_uses($this->getClass());
    }
} 