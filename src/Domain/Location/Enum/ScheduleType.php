<?php
/**
 * Created by PhpStorm.
 * Date: 23.02.2025
 * Time: 15:32
 */

namespace App\Domain\Location\Enum;

enum ScheduleType: string
{
    case Regular = 'regular';
    case Special = 'special';
    case Vacation = 'vacation';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        $result = [];
        foreach (self::cases() as $case) {
            $result[$case->value] = $case->value;
        }

        return $result;
    }
}
