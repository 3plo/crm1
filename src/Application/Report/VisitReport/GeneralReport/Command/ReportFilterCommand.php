<?php
/**
 * Created by PhpStorm.
 * Date: 13.03.2024
 * Time: 21:26
 */

namespace App\Application\Report\VisitReport\GeneralReport\Command;

class ReportFilterCommand
{
    public function __construct(
        private readonly \DateTimeImmutable $dateFrom,
        private readonly \DateTimeImmutable $dateTill,
        private readonly null|string $locationId,
        private readonly null|string $productId,
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

    public function getLocationId(): ?string
    {
        return $this->locationId;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }
}
