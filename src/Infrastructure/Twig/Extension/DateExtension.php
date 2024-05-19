<?php
/**
 * Created by PhpStorm.
 * Date: 19.05.2024
 * Time: 14:24
 */

namespace App\Infrastructure\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('day_name', [$this, 'getDayName']),
        ];
    }

    public function getDayName($dayNumber)
    {
        $days = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];

        return $days[$dayNumber] ?? 'Invalid day';
    }
}
