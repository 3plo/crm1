<?php
/**
 * Created by PhpStorm.
 * Date: 02.03.2024
 * Time: 22:11
 */

namespace App\View\Request\Card;

use App\View\Request\FormRequestInterface;

class CreateRequest implements FormRequestInterface
{
    private string $product;
    private string $price;

    public function getProduct(): string
    {
        return $this->product;
    }

    public function getPrice(): string
    {
        return $this->price;
    }
}
