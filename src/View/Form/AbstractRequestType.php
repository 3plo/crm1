<?php
/**
 * Created by PhpStorm.
 * Date: 11.02.2024
 * Time: 19:47
 */

namespace App\View\Form;

use Symfony\Component\Form\AbstractType;

abstract class AbstractRequestType extends AbstractType
{
    abstract public static function getRequestClass(): string;
}
