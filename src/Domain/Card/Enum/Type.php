<?php
/**
 * Created by PhpStorm.
 * Date: 28.02.2024
 * Time: 22:41
 */

namespace App\Domain\Card\Enum;

enum Type: string
{
    case SingleDayTicket = 'single_day_ticket';
    case MonthlyTicket = 'monthly_ticket';
    case YearlyTicket = 'yearly_ticket';
}

