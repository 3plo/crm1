<?php
/**
 * Created by PhpStorm.
 * Date: 08.04.2024
 * Time: 23:11
 */

namespace App\View\Form\Constraint\User;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UserExist extends Constraint
{
    public function getMessage(): string
    {
        return 'User not exist';
    }
}
