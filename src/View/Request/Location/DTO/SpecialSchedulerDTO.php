<?php
/**
 * Created by PhpStorm.
 * Date: 18.02.2024
 * Time: 19:19
 */

namespace App\View\Request\Location\DTO;

use JMS\Serializer\Annotation as JMS;

class SpecialSchedulerDTO
{
    #[JMS\SerializedName('timeFrom')]
    #[JMS\Type('DateTimeImmutable<"H:i", "+00:00", ["' . \DateTimeInterface::ATOM . '"]>')]
    private \DateTimeImmutable $timeFrom;

    #[JMS\SerializedName('timeTill')]
    #[JMS\Type('DateTimeImmutable<"H:i", "+00:00", ["' . \DateTimeInterface::ATOM . '"]>')]
    private \DateTimeImmutable $timeTill;

    #[JMS\SerializedName('dateFrom')]
    #[JMS\Type('DateTimeImmutable<"Y-m-d", "+00:00", ["' . \DateTimeInterface::ATOM . '"]>')]
    private \DateTimeImmutable $dateFrom;

    #[JMS\SerializedName('dateTill')]
    #[JMS\Type('DateTimeImmutable<"Y-m-d", "+00:00", ["' . \DateTimeInterface::ATOM . '"]>')]
    private null|\DateTimeImmutable $dateTill = null;

    public function getTimeFrom(): \DateTimeImmutable
    {
        return $this->timeFrom;
    }

    public function getTimeTill(): \DateTimeImmutable
    {
        return $this->timeTill;
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
