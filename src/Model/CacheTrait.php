<?php

namespace Ai\AdminBundle\Model;


trait CacheTrait
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="no_cache", type="boolean")
     */
    private $noCache=false;

    /**
     * @var integer
     *
     * @ORM\Column(name="cache_timeout", type="integer")
     */
    private $cacheTimeout=0;

    /**
     * Set noCache
     *
     * @param boolean $noCache
     * @return $this
     */
    public function setNoCache($noCache)
    {
        $this->noCache = $noCache;

        return $this;
    }

    /**
     * Get noCache
     *
     * @return boolean
     */
    public function getNoCache()
    {
        return $this->noCache;
    }

    /**
     * Set cacheTimeout
     *
     * @param integer $cacheTimeout
     * @return $this
     */
    public function setCacheTimeout($cacheTimeout)
    {
        $this->cacheTimeout = $cacheTimeout;

        return $this;
    }

    /**
     * Get cacheTimeout
     *
     * @return integer
     */
    public function getCacheTimeout()
    {
        return $this->cacheTimeout;
    }
}