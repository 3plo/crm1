<?php
/**
 * Created by PhpStorm.
 * Date: 29.02.2024
 * Time: 21:50
 */

namespace App\Domain\Barcode;

use App\Domain\Location\Location;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
class ScanLog
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $status;

    #[ORM\ManyToOne(targetEntity: Location::class)]
    private Location $location;

    #[ORM\Column(type: Types::STRING)]
    private string $barcodeString;

    #[ORM\ManyToOne(targetEntity: Barcode::class)]
    #[ORM\JoinColumn(nullable: true)]
    private null|Barcode $barcode = null;

    #[ORM\Column(type: Types::STRING)]
    private string $message;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private \DateTimeImmutable $createdAt;

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

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getBarcodeString(): string
    {
        return $this->barcodeString;
    }

    public function setBarcodeString(string $barcodeString): self
    {
        $this->barcodeString = $barcodeString;
        return $this;
    }

    public function getBarcode(): null|Barcode
    {
        return $this->barcode;
    }

    public function setBarcode(null|Barcode $barcode): self
    {
        $this->barcode = $barcode;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
