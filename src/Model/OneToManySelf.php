<?php

namespace Ai\AdminBundle\Model;


trait OneToManySelf
{
    /**
     * @ORM\OneToMany(targetEntity=, mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity=, inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
}