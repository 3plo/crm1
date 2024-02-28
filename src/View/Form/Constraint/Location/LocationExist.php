<?php
/**
 * Created by PhpStorm.
 * Date: 28.02.2024
 * Time: 21:43
 */

namespace App\View\Form\Constraint\Location;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class LocationExist extends Constraint
{
    public function getMessage(): string
    {
        return 'Location not exist';
    }
}
