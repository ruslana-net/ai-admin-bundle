<?php

namespace Ai\AdminBundle\Model;


trait DescrTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="descr", type="text", nullable=true)
     */
    private $descr;

    /**
     * Set descr
     *
     * @param string $descr
     *
     * @return $this
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
    }

    /**
     * Get descr
     *
     * @return string
     */
    public function getDescr()
    {
        return $this->descr;
    }
}