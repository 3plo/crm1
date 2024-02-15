<?php
/**
 * Created by PhpStorm.
 * Date: 04.02.2024
 * Time: 22:51
 */

namespace App\View\Scheme\Product;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class ProductPriceCreateScheme
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
            'productId' => [
                new Assert\NotBlank(),
                new Assert\Type('string'),
            ],
            'title' => [
                new Assert\NotBlank(),
                new Assert\Type('string'),
            ],
            'amountInUAH' => [
                new Assert\NotBlank(),
                new Assert\Positive(),
                new Assert\Type('numeric'),
            ],
        ];
    }
}
