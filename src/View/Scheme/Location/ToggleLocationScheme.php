<?php
/**
 * Created by PhpStorm.
 * Date: 08.04.2024
 * Time: 21:28
 */

namespace App\View\Scheme\Location;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class ToggleLocationScheme
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
            'locationId' => [
                new Assert\NotBlank(),
                new Assert\Type('string'),
            ],
        ];
    }

}
