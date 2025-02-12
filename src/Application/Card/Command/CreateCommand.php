<?php
/**
 * Created by PhpStorm.
 * Date: 02.03.2024
 * Time: 22:36
 */

namespace App\Application\Card\Command;

use App\Domain\Product\Price;
use App\Domain\Product\Product;

readonly class CreateCommand
{
    public function __construct(
        private Product $product,
        private Price $price,
    ) {
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }
}
