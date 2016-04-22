<?php

namespace Ai\AdminBundle\Model;

use Gedmo\Mapping\Annotation as Gedmo;

trait NameSlugTrait
{
    /**
     * @var string
     * @Gedmo\Slug(separator="_", style="default", updatable=true, unique=true, fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * Set slug
     *
     * @param string $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}