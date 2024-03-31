<?php
/**
 * Created by PhpStorm.
 * Date: 31.03.2024
 * Time: 15:31
 */

namespace App\Application\Report\VisitReport\TrafficReport\Command;

class ReportFilterCommand
{
    public function __construct(
        private readonly string $locationId,
        private readonly \DateTimeImmutable $dateFrom,
        private readonly \DateTimeImmutable $dateTill,
        private readonly null|string $productId,
    ) {
    }

    public function getLocationId(): string
    {
        return $this->locationId;
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
}
