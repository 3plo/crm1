<?php
/**
 * Created by PhpStorm.
 * Date: 27.01.2024
 * Time: 0:24
 */

namespace App\Domain\Card;

use App\Domain\Card\Enum\Type;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
class Card
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $enabled = true;

    #[ORM\Column(type: Types::STRING, enumType: Type::class)]
    private Type $type;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['default' => null])]
    private null|int $maxUsage = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $countUsage;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $validFrom;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $validTill;

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

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function setType(Type $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getMaxUsage(): null|int
    {
        return $this->maxUsage;
    }

    public function setMaxUsage(null|int $maxUsage): self
    {
        $this->maxUsage = $maxUsage;
        return $this;
    }

    public function getCountUsage(): int
    {
        return $this->countUsage;
    }

    public function setCountUsage(int $countUsage): self
    {
        $this->countUsage = $countUsage;
        return $this;
    }

    public function getValidFrom(): \DateTimeImmutable
    {
        return $this->validFrom;
    }

    public function setValidFrom(\DateTimeImmutable $validFrom): self
    {
        $this->validFrom = $validFrom;
        return $this;
    }

    public function getValidTill(): \DateTimeImmutable
    {
        return $this->validTill;
    }

    public function setValidTill(\DateTimeImmutable $validTill): self
    {
        $this->validTill = $validTill;
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