<?php

namespace Ai\AdminBundle\Model;


trait FileTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=100)
     */
    private $file;
    
    /**
     * @var string
     *
     * @ORM\Column(name="fileExt", type="string", length=100)
     */
    private $fileExt;

    /**
     * Set file
     *
     * @param string $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * Set fileExt
     *
     * @param string $fileExt
     * @return $this
     */
    public function setFileExt($fileExt)
    {
        $this->fileExt = $fileExt;

        return $this;
    }

    /**
     * Get fileExt
     *
     * @return string
     */
    public function getFileExt()
    {
        return $this->fileExt;
    }
}