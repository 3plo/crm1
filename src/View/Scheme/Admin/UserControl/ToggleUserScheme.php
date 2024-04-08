<?php
/**
 * Created by PhpStorm.
 * Date: 08.04.2024
 * Time: 21:28
 */

namespace App\View\Scheme\Admin\UserControl;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class ToggleUserScheme
{
    /**
     * @return Constraint[]
     */
    public static function getSchemeConstraintList(): array
    {
        return [
            'enabled' => [
                new Assert\NotNull(),
                new Assert\Type('bool'),
            ],
            'userId' => [
                new Assert\NotBlank(),
                new Assert\Type('string'),
            ],
        ];
    }

}
