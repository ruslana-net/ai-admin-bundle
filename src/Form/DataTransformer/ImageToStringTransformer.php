<?php

namespace Ai\AdminBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Ai\AdminBundle\Services\ImageManager;

class ImageToStringTransformer implements DataTransformerInterface
{
    private $imageManager;
    private $options;

    /**
     * ImageToStringTransformer constructor.
     * @param $imageManager
     */
    public function __construct(ImageManager $imageManager, array $options)
    {
        $this->imageManager = $imageManager;
        $this->options = $options;
    }

    /**
     * @param mixed $imageName
     * @return mixed
     */
    public function transform($imageName)
    {
        if(!$imageName){
            return '';
        }

        $imageData = $this->imageManager->getImageData($this->options['oneup_uploader_id'], $imageName);

        return json_encode([$imageData]);
    }

    /**
     * Transforms a string (number) to an object (issue). TODO change
     *
     * @param  string $issueNumber
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($imageJson)
    {
        if(!$imageJson) return '';

        $imageData = json_decode($imageJson, true);
        if(empty($imageData)) return '';

        $imageData = $imageData[0];
        $fileName = '';

        if( array_key_exists('type', $imageData)
            && array_key_exists('name', $imageData)
        ){
            $type =  $imageData['type'];
            $fileName =  $imageData['name'];
            $uploaded =  array_key_exists('uploaded', $imageData) ? $imageData['uploaded'] : false;
            $deleted =  array_key_exists('deleted', $imageData) ? $imageData['deleted'] : false;

            if($uploaded) {
                $this->imageManager->orphanageUploads($type, $fileName);
            }

            if($deleted){
                $this->imageManager->removeFile($type, $fileName);
                return '';
            }
        }

        return $fileName;
    }
}