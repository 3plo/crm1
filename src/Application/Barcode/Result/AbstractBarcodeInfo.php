<?php
/**
 * Created by PhpStorm.
 * Date: 01.06.2024
 * Time: 12:15
 */

namespace App\Application\Barcode\Result;

abstract class AbstractBarcodeInfo implements BarcodeInfoInterface
{
    public function __construct(
        private readonly string             $message,
        private readonly \DateTimeImmutable $validFrom,
        private readonly \DateTimeImmutable $validTill,
        private readonly int                $countUsage,
        private readonly int                $maxCountUsage,
        private readonly bool               $isActive,
    ) {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getValidFrom(): \DateTimeImmutable
    {
        return $this->validFrom;
    }

    public function getValidTill(): \DateTimeImmutable
    {
        return $this->validTill;
    }

    public function getCountUsage(): int
    {
        return $this->countUsage;
    }

    public function getMaxCountUsage(): int
    {
        return $this->maxCountUsage;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
