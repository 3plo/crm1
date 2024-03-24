<?php
/**
 * Created by PhpStorm.
 * Date: 13.03.2024
 * Time: 19:54
 */

namespace App\Application\Report\VisitReport\GeneralReport\Result;

class Item
{
    public function __construct(
        private readonly string $locationTitle,
        private readonly string $productTitle,
        private readonly int $countSuccess,
        private readonly int $countDecline,
    ) {
    }

    public function getLocationTitle(): string
    {
        return $this->locationTitle;
    }

    public function getProductTitle(): string
    {
        return $this->productTitle;
    }

    public function getCountSuccess(): int
    {
        return $this->countSuccess;
    }

    public function getCountDecline(): int
    {
        return $this->countDecline;
    }
}
