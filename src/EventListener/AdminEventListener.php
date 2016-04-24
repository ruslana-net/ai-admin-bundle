<?php

namespace Ai\AdminBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Sonata\AdminBundle\Event\PersistenceEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\PersistentCollection;

class AdminEventListener
{
    use ContainerAwareTrait;

    /**
     * @Inject("ai_gallery.image_manager")
     * @var \Ai\AdminBundle\Services\ImageManager
     */
    protected $imageManager;

    /**
     * @param PersistenceEvent $event
     */
    public function create(PersistenceEvent $event)
    {

    }

    /**
     * @param PersistenceEvent $event
     */
    public function update(PersistenceEvent $event)
    {
        var_dump($this->imageManager);
        var_dump('test');die();
    }

    /**
     * @param PersistenceEvent $event
     */
    public function remove(PersistenceEvent $event)
    {

    }
}