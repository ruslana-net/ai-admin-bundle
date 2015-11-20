<?php

namespace Ai\AdminBundle\Twig\Extension;

use \Twig_Extension;
use \Twig_SimpleFunction;

class FileExtension extends Twig_Extension
{

    /**
     * Return the functions registered as twig extensions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('file_exists', 'file_exists'),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_file';
    }
}