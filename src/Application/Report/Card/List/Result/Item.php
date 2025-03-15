<?php
/**
 * Created by PhpStorm.
 * Date: 15.03.2025
 * Time: 0:23
 */

namespace App\Application\Report\Card\List\Result;

readonly class Item
{
    public function __construct(
        private string             $id,
        private string             $activeBarcode,
        private string             $priceTitle,
        private string             $productTitle,
        private \DateTimeImmutable $validFrom,
        private \DateTimeImmutable $validTill,
        private \DateTimeImmutable $createdAt,
        private string             $userName,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getActiveBarcode(): string
    {
        return $this->activeBarcode;
    }

    public function getPriceTitle(): string
    {
        return $this->priceTitle;
    }

    public function getProductTitle(): string
    {
        return $this->productTitle;
    }

    public function getValidFrom(): \DateTimeImmutable
    {
        return $this->validFrom;
    }

    public function getValidTill(): \DateTimeImmutable
    {
        return $this->validTill;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }
}
