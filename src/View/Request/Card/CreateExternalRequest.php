<?php
/**
 * Created by PhpStorm.
 * Date: 02.03.2024
 * Time: 22:11
 */

namespace App\View\Request\Card;

use App\View\Request\FormRequestInterface;

class CreateExternalRequest implements FormRequestInterface
{
    private string $product;
    private string $price;
    private string $barcode;

    public function getProduct(): string
    {
        return $this->product;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getBarcode(): string
    {
        return $this->barcode;
    }
}
