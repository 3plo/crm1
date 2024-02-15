<?php
/**
 * Created by PhpStorm.
 * Date: 08.02.2024
 * Time: 21:31
 */

namespace App\Domain\Location;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
class SpecialScheduler
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[JMS\Exclude]
    #[ORM\ManyToOne(targetEntity: Location::class)]
    private Location $location;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $dateFrom;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['default' => null])]
    private null|\DateTimeImmutable $dateTill = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private string $timeFrom;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private string $timeTill;

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

    public function getTimeFrom(): string
    {
        return $this->timeFrom;
    }

    public function setTimeFrom(string $timeFrom): self
    {
        $this->timeFrom = $timeFrom;
        return $this;
    }

    public function getTimeTill(): string
    {
        return $this->timeTill;
    }

    public function setTimeTill(string $timeTill): self
    {
        $this->timeTill = $timeTill;
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
