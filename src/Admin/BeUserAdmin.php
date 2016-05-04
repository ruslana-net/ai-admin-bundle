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
            ->add('groups')
            ->add('id')
            ->add('username', null)
            ->add('email', null)
            ->add('firstName', null)
            ->add('lastName', null)
            ->add('phone', null)
            ->add('skype', null)
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('username', null)
            ->add('email', null)
            ->add('avatar', 'ai_admin_image_uploader', [
                'oneup_uploader_id' => 'be_user_avatar',
                'template' => 'AiAdminBundle:Admin:image_preview_list.html.twig'
            ])
            ->add('firstName', null)
            ->add('lastName', null)
            ->add('phone', null)
            ->add('skype', null)
            ->add('groups', null)
            ->add('enabled', null, ['editable' => true])
            ->add('_action', 'actions', [
                'actions' =>[
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ]
            ])
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $passwordRequired = $this->getSubject()->getId() ? false : true;

        $formMapper
            ->add('username', null)
            ->add('email', 'email')
            ->add('avatar', 'ai_admin_image_uploader', [
                'oneup_uploader_id' => 'be_user_avatar',
                'required' => false,
            ])
            ->add('plainPassword', 'repeated', [
                'required' => $passwordRequired,
                'type' => 'password',
                'invalid_message' => 'Введите пароль повторно.',
                'options' => ['label' => 'form.label_password'],
            ])
            ->add('groups', null)
//            ->add('roles', 'choice', [
//                'choices' => BeUser::$ADMIN_ROLES,
//                'multiple'  => true
//            ])
            ->add('firstName', null, ['required' => false])
            ->add('lastName', null, ['required' => false])
            ->add('phone', null, ['required' => false])
            ->add('skype', null, ['required' => false])
            ->add('skype', null, ['required' => false])
            ->add('hideLeftMenu', null, ['required' => false])
            ->add('enabled', null, ['required' => false])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('username', null)
            ->add('email', null)
            ->add('avatar', 'ai_admin_image_uploader', [
                'oneup_uploader_id' => 'be_user_avatar',
                'template' => 'AiAdminBundle:Admin:image_preview_show.html.twig'
            ])
            ->add('firstName', null)
            ->add('lastName', null)
            ->add('phone', null)
            ->add('skype', null)
            ->add('hideLeftMenu', null, ['required' => false])
        ;
    }
}
