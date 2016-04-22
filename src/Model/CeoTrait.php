<?php

namespace Ai\AdminBundle\Model;


trait CeoTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="ceo_title", type="string", length=255, nullable=true)
     */
    private $ceoTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="ceo_keywords", type="string", length=255, nullable=true)
     */
    private $ceoKeywords;

    /**
     * @var string
     *
     * @ORM\Column(name="ceo_description", type="string", length=255, nullable=true)
     */
    private $ceoDescription;

    /**
     * Set ceoTitle
     *
     * @param string $ceoTitle
     * @return $this
     */
    public function setCeoTitle($ceoTitle)
    {
        $this->ceoTitle = $ceoTitle;

        return $this;
    }

    /**
     * Get ceoTitle
     *
     * @return string
     */
    public function getCeoTitle()
    {
        return $this->ceoTitle;
    }

    /**
     * Set ceoKeywords
     *
     * @param string $ceoKeywords
     * @return $this
     */
    public function setCeoKeywords($ceoKeywords)
    {
        $this->ceoKeywords = $ceoKeywords;

        return $this;
    }

    /**
     * Get ceoKeywords
     *
     * @return string
     */
    public function getCeoKeywords()
    {
        return $this->ceoKeywords;
    }

    /**
     * Set ceoDescription
     *
     * @param string $ceoDescription
     * @return $this
     */
    public function setCeoDescription($ceoDescription)
    {
        $this->ceoDescription = $ceoDescription;

        return $this;
    }

    /**
     * Get ceoDescription
     *
     * @return string
     */
    public function getCeoDescription()
    {
        return $this->ceoDescription;
    }
}