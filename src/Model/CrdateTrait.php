<?php

namespace Ai\AdminBundle\Model;

use Gedmo\Mapping\Annotation as Gedmo;

trait CrdateTrait
{
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="crdate", type="datetime")
     */
    private $crdate;

    /**
     * Set crdate
     *
     * @param \DateTime $crdate
     * @return Page
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;

        return $this;
    }

    /**
     * Get crdate
     *
     * @return \DateTime
     */
    public function getCrdate()
    {
        return $this->crdate;
    }
}