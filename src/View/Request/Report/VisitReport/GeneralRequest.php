<?php
/**
 * Created by PhpStorm.
 * Date: 16.03.2024
 * Time: 10:12
 */

namespace App\View\Request\Report\VisitReport;

use App\View\Request\FormRequestInterface;
use JMS\Serializer\Annotation as JMS;

class GeneralRequest implements FormRequestInterface
{
    #[JMS\SerializedName('dateFrom')]
    #[JMS\Type('DateTimeImmutable<"Y-m-d 00:00:00", "+00:00", ["' . \DateTimeInterface::ATOM . '"]>')]
    private \DateTimeImmutable $dateFrom;


    #[JMS\SerializedName('dateTill')]
    #[JMS\Type('DateTimeImmutable<"Y-m-d 23:59:59", "+00:00", ["' . \DateTimeInterface::ATOM . '"]>')]
    private \DateTimeImmutable $dateTill;

    private null|string $location;

    private null|string $product;

    public function getDateFrom(): \DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function getDateTill(): \DateTimeImmutable
    {
        return $this->dateTill;
    }

    public function getLocation(): null|string
    {
        return $this->location;
    }

    public function getProduct(): null|string
    {
        return $this->product;
    }
}
