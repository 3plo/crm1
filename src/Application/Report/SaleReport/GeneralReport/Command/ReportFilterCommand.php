<?php
/**
 * Created by PhpStorm.
 * Date: 13.03.2024
 * Time: 21:26
 */

namespace App\Application\Report\SaleReport\GeneralReport\Command;

readonly class ReportFilterCommand
{
    public function __construct(
        private \DateTimeImmutable $dateFrom,
        private \DateTimeImmutable $dateTill,
        private null|string $productId,
        private null|string $userId,
    ) {
    }

    public function getDateFrom(): \DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function getDateTill(): \DateTimeImmutable
    {
        return $this->dateTill;
    }

    public function getProductId(): null|string
    {
        return $this->productId;
    }

    public function getUserId(): null|string
    {
        return $this->userId;
    }
}
