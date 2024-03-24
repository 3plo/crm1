<?php
/**
 * Created by PhpStorm.
 * Date: 02.03.2024
 * Time: 22:36
 */

namespace App\Application\Card\Command;

use App\Domain\Product\Product;

class CreateCommand
{
    public function __construct(
        private readonly Product $product,
        private readonly string $priceId,
    ) {
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getPriceId(): string
    {
        return $this->priceId;
    }
}
