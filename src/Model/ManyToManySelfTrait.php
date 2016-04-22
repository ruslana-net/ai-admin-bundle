<?php

namespace Ai\AdminBundle\Model;


trait ManyToManySelfTrait
{
    /**
     * @ORM\ManyToMany(targetEntity=, mappedBy="parents")
     */
    private $children;

    /**
     * @ORM\ManyToMany(targetEntity=, inversedBy="children")
     * @ORM\JoinTable(name="parents",
     *      joinColumns={@ORM\JoinColumn(name="child_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id")}
     *      )
     */
    private $parents;

    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parents = new \Doctrine\Common\Collections\ArrayCollection();
    }
}