<?php
/**
 * Created by PhpStorm.
 * Date: 16.03.2024
 * Time: 10:12
 */

namespace App\View\Request\Report\VisitReport;

use App\View\Request\FormRequestInterface;
use JMS\Serializer\Annotation as JMS;

class TrafficReportRequest implements FormRequestInterface
{
    #[JMS\SerializedName('dateFrom')]
    #[JMS\Type('DateTimeImmutable<"Y-m-d 00:00:00", "+00:00", ["' . \DateTimeInterface::ATOM . '"]>')]
    private \DateTimeImmutable $dateFrom;


    #[JMS\SerializedName('dateTill')]
    #[JMS\Type('DateTimeImmutable<"Y-m-d 23:59:59", "+00:00", ["' . \DateTimeInterface::ATOM . '"]>')]
    private \DateTimeImmutable $dateTill;

    private string $location;

    private null|string $product = null;

    public function getDateFrom(): \DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function setDateFrom(\DateTimeImmutable $dateFrom): self
    {
        $this->dateFrom = $dateFrom;
        return $this;
    }

    public function getDateTill(): \DateTimeImmutable
    {
        return $this->dateTill;
    }

    public function setDateTill(\DateTimeImmutable $dateTill): self
    {
        $this->dateTill = $dateTill;
        return $this;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getProduct(): null|string
    {
        return $this->product;
    }

    public function setProduct(null|string $product): self
    {
        $this->product = $product;
        return $this;
    }
}
