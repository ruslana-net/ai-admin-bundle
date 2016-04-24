<?php

namespace Ai\AdminBundle\Intarface;


interface AdminIconIntarface
{
    /**
     * @param $adminIcon
     * @return string
     */
    public function setAdminIcon($adminIcon);

    /**
     * @return string
     */
    public function getAdminIcon();
}