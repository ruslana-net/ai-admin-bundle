<?php

namespace Ai\AdminBundle\Model;


trait PaidTrait
{
    /**
     * @var bool
     *
     * @ORM\Column(name="paid", type="boolean")
     */
    private $paid=false;

    /**
     * Set paid
     *
     * @param boolean $paid
     *
     * @return $this
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get paid
     *
     * @return bool
     */
    public function getPaid()
    {
        return $this->paid;
    }
}