<?php
/**
 * Created by PhpStorm.
 * Date: 07.02.2024
 * Time: 23:40
 */

namespace App\Domain\Location;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
class RegularScheduler
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[JMS\Exclude]
    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'regularSchedulerList')]
    private Location $location;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $dateFrom;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['default' => null])]
    private null|\DateTimeImmutable $dateTill = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $dayNumber;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private \DateTimeImmutable $timeFrom;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private \DateTimeImmutable $timeTill;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $enabled = true;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private \DateTimeImmutable $updatedAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getDateFrom(): \DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function setDateFrom(\DateTimeImmutable $dateFrom): self
    {
        $this->dateFrom = $dateFrom;
        return $this;
    }

    public function getDateTill(): null|\DateTimeImmutable
    {
        return $this->dateTill;
    }

    public function setDateTill(null|\DateTimeImmutable $dateTill): self
    {
        $this->dateTill = $dateTill;
        return $this;
    }

    public function getDayNumber(): int
    {
        return $this->dayNumber;
    }

    public function setDayNumber(int $dayNumber): self
    {
        $this->dayNumber = $dayNumber;
        return $this;
    }

    public function getTimeFrom(): \DateTimeImmutable
    {
        return $this->timeFrom;
    }

    public function setTimeFrom(\DateTimeImmutable $timeFrom): self
    {
        $this->timeFrom = $timeFrom;
        return $this;
    }

    public function getTimeTill(): \DateTimeImmutable
    {
        return $this->timeTill;
    }

    public function setTimeTill(\DateTimeImmutable $timeTill): self
    {
        $this->timeTill = $timeTill;
        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
