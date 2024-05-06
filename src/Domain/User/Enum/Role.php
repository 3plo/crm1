<?php
/**
 * Created by PhpStorm.
 * Date: 08.04.2024
 * Time: 22:59
 */

namespace App\Domain\User\Enum;

enum Role: string
{
    case RoleUser = 'user';
    case RoleAdmin = 'admin';

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
