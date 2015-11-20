<?php

namespace Ai\AdminBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Ai\AdminBundle\Entity\BeUser;

class BeUserAdmin extends DefaultAdmin
{
    protected $baseRouteName = 'admin_beuser';

    protected $baseRoutePattern = '/ai/be_user';

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('username', null, array('label' => 'Логин'))
            ->add('email', null, array('label' => 'Email'))
            ->add('firstName', null, array('label' => 'Имя'))
            ->add('lastName', null, array('label' => 'Фамилия'))
            ->add('phone', null, array('label' => 'Телефон'))
            ->add('skype', null, array('label' => 'Скайп'))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('username', null, array('label' => 'Логин'))
            ->add('email', null, array('label' => 'Email'))
            ->add('avatar', null, array('template' => 'AiAdminBundle:Admin:image_preview_list.html.twig'))
            ->add('firstName', null, array('label' => 'Имя'))
            ->add('lastName', null, array('label' => 'Фамилия'))
            ->add('phone', null, array('label' => 'Телефон'))
            ->add('skype', null, array('label' => 'Скайп'))
            ->add('enabled', null, array('label' => 'Включен', 'editable' => true))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('username', null, array('label' => 'Логин'))
            ->add('email', 'email', array('label' => 'Email'))
            ->add('file', 'file', $this->getFileOptions(array('label' => 'Avatar Title', 'required' => false)))
            ->add('plainPassword', 'repeated', array(
                'required' => false,
                'type' => 'password',
                'invalid_message' => 'Введите пароль повторно.',
                'options' => array('label' => 'Пароль'),
            ))
            ->add('groups', null, array('label' => 'Группа'))
            ->add('roles', 'choice', array(
                'choices' => BeUser::$ADMIN_ROLES,
                'label' => 'Права доступа',
                'multiple'  => true
            ))
            ->add('firstName', null, array('label' => 'Имя', 'required' => false))
            ->add('lastName', null, array('label' => 'Фамилия', 'required' => false))
            ->add('phone', null, array('label' => 'Телефон', 'required' => false))
            ->add('skype', null, array('label' => 'Скайп', 'required' => false))
            ->add('skype', null, array('label' => 'Скайп', 'required' => false))
            ->add('enabled', null, array('label' => 'Включен', 'required' => false))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('username', null, array('label' => 'Логин'))
            ->add('email', null, array('label' => 'Email'))
            ->add('firstName', null, array('label' => 'Имя'))
            ->add('lastName', null, array('label' => 'Фамилия'))
            ->add('phone', null, array('label' => 'Телефон'))
            ->add('skype', null, array('label' => 'Скайп'))
        ;
    }
}
