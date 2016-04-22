<?php

namespace Ai\AdminBundle\Model;

use Gedmo\Mapping\Annotation as Gedmo;

trait TstampTrait
{
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="tstamp", type="datetime")
     */
    private $tstamp;

    /**
     * Set tstamp
     *
     * @param \DateTime $tstamp
     * @return $this
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;

        return $this;
    }

    /**
     * Get tstamp
     *
     * @return \DateTime
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }
}