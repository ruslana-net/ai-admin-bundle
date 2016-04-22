<?php

namespace Ai\AdminBundle\Model;


class SlugTrait
{
    /**
     * @var string
     * @Gedmo\Slug(separator="_", style="default", updatable=true, unique=true, fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * Set slug
     *
     * @param string $slug
     * @return Page
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