<?php
/**
 * Created by PhpStorm.
 * Date: 08.04.2024
 * Time: 21:28
 */

namespace App\View\Scheme\Location;

use App\Domain\Location\Enum\ScheduleType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class ToggleLocationScheduleScheme
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
            'type' => [
                new Assert\NotBlank(),
                new Assert\Choice(callback: [ScheduleType::class, 'values']),
            ],
            'schedulerId' => [
                new Assert\NotBlank(),
                new Assert\Type('string'),
            ],
        ];
    }
}
