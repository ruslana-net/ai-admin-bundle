<?php

namespace Ai\AdminBundle\Model;


trait ImageTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=100)
     */
    private $image;
    
    /**
     * @var string
     *
     * @ORM\Column(name="imageExt", type="string", length=100)
     */
    private $imageExt;

    /**
     * Set image
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * Set imageExt
     *
     * @param string $imageExt
     * @return $this
     */
    public function setImageExt($imageExt)
    {
        $this->imageExt = $imageExt;

        return $this;
    }

    /**
     * Get imageExt
     *
     * @return string
     */
    public function getImageExt()
    {
        return $this->imageExt;
    }
}