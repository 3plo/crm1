<?php
/**
 * Created by PhpStorm.
 * Date: 16.03.2024
 * Time: 10:12
 */

namespace App\View\Request\Report\SaleReport;

use App\View\Request\FormRequestInterface;
use JMS\Serializer\Annotation as JMS;

class GeneralReportRequest implements FormRequestInterface
{
    #[JMS\SerializedName('dateFrom')]
    #[JMS\Type('DateTimeImmutable<"Y-m-d 00:00:00", "+00:00", ["' . \DateTimeInterface::ATOM . '"]>')]
    private \DateTimeImmutable $dateFrom;


    #[JMS\SerializedName('dateTill')]
    #[JMS\Type('DateTimeImmutable<"Y-m-d 23:59:59", "+00:00", ["' . \DateTimeInterface::ATOM . '"]>')]
    private \DateTimeImmutable $dateTill;

    private null|string $product;

    private null|string $user;

    public function getDateFrom(): \DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function getDateTill(): \DateTimeImmutable
    {
        return $this->dateTill;
    }

    public function getProduct(): null|string
    {
        return $this->product;
    }

    public function getUser(): null|string
    {
        return $this->user;
    }
}
