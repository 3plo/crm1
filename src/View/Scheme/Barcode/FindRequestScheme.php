<?php
/**
 * Created by PhpStorm.
 * Date: 26.02.2024
 * Time: 22:19
 */

namespace App\View\Scheme\Barcode;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class FindRequestScheme
{
    /**
     * @return Constraint[]
     */
    public static function getSchemeConstraintList(): array
    {
        return [
            'locationId' => [
                new Assert\NotBlank(),
                new Assert\Type('string'),
            ],
            'barcode' => [
                new Assert\NotBlank(),
                new Assert\Type('string'),
            ],
        ];
    }
}
