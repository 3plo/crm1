<?php
/**
 * Created by PhpStorm.
 * Date: 30.04.2024
 * Time: 21:01
 */

namespace App\View\Request;

interface LocationAccessInterface
{
    /**
     * @return string[]
     */
    public function getLocationList(): array;
}
