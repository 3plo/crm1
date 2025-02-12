<?php
/**
 * Created by PhpStorm.
 * Date: 13.03.2024
 * Time: 19:54
 */

namespace App\Application\Report\SaleReport\GeneralReport\Result;

use App\Domain\User\User;

readonly class Item
{
    public function __construct(
        private null|string $userName,
        private string      $priceTitle,
        private string      $productTitle,
        private int         $countCard,
        private float       $sumCard,
    )
    {
    }

    public function getUserName(): null|string
    {
        return trim($this->userName);
    }

    public function getPriceTitle(): string
    {
        return $this->priceTitle;
    }

    public function getProductTitle(): string
    {
        return $this->productTitle;
    }

    public function getCountCard(): int
    {
        return $this->countCard;
    }

    public function getSumCard(): float
    {
        return $this->sumCard;
    }
}
