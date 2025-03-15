<?php
/**
 * Created by PhpStorm.
 * Date: 26.02.2024
 * Time: 21:14
 */

namespace App\Application\Barcode\Result;

readonly class BarcodeHandleResult
{
    public function __construct(
        private bool $status,
        private string $message,
        private null|bool $isInDelta = null,
    ) {
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function isInDelta(): null|bool
    {
        return $this->isInDelta;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
