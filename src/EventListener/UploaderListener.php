<?php

namespace Ai\AdminBundle\EventListener;

use Oneup\UploaderBundle\Event\PostUploadEvent;

class UploaderListener
{
    /**
     * Return filename to FineUploader
     *
     * @param PostUploadEvent $event
     * @return \Oneup\UploaderBundle\Uploader\Response\ResponseInterface
     */
    public function onUpload(PostUploadEvent $event)
    {
        $response = $event->getResponse();
        /** @var \Symfony\Component\HttpFoundation\File\File $file */
        $file = $event->getFile();
        $response['qqfile'] = $file->getFilename();

        return $response;
    }
}