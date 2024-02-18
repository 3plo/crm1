<?php
/**
 * Created by PhpStorm.
 * Date: 18.02.2024
 * Time: 19:19
 */

namespace App\View\Request\Location\DTO;

use JMS\Serializer\Annotation as JMS;

class VacationSchedulerDTO
{
    #[JMS\SerializedName('dayNumber')]
    private int $dayNumber;

    private string $title;

    #[JMS\SerializedName('dateFrom')]
    #[JMS\Type('DateTimeImmutable<"Y-m-d", "+00:00", ["' . \DateTimeInterface::ATOM . '"]>')]
    private \DateTimeImmutable $dateFrom;

    #[JMS\SerializedName('dateTill')]
    #[JMS\Type('DateTimeImmutable<"Y-m-d", "+00:00", ["' . \DateTimeInterface::ATOM . '"]>')]
    private null|\DateTimeImmutable $dateTill = null;

    public function getDayNumber(): int
    {
        return $this->dayNumber;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDateFrom(): \DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function getDateTill(): null|\DateTimeImmutable
    {
        return $this->dateTill;
    }
}
