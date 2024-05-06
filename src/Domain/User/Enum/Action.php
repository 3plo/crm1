<?php
/**
 * Created by PhpStorm.
 * Date: 08.04.2024
 * Time: 22:22
 */

namespace App\Domain\User\Enum;

enum Action: string
{
    case ProductList = 'product_list';
    case ProductCreate = 'product_create';
    case ProductActivate = 'product_activate';

    case LocationList = 'location_list';
    case LocationCreate = 'location_create';
    case LocationActivate = 'location_activate';

    case EntranceControl = 'entrance_control';

    case Sell = 'sell';

    public static function toArray(): array
    {
        $result = [];
        foreach (self::cases() as $case) {
            $value = $case->value;
            $result[$value] = $value;
        }

        return $result;
    }
}

