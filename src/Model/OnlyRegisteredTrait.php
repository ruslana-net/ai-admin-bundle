<?php

namespace Ai\AdminBundle\Model;

trait OnlyRegisteredTrait
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="only_registered", type="boolean")
     */
    private $onlyRegistered=false;

    /**
     * Set onlyRegistered
     *
     * @param boolean $onlyRegistered
     * @return $this
     */
    public function setOnlyRegistered($onlyRegistered)
    {
        $this->onlyRegistered = $onlyRegistered;

        return $this;
    }

    /**
     * Get onlyRegistered
     *
     * @return boolean
     */
    public function getOnlyRegistered()
    {
        return $this->onlyRegistered;
    }
}