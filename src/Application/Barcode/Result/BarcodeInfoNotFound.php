<?php
/**
 * Created by PhpStorm.
 * Date: 01.06.2024
 * Time: 12:15
 */

namespace App\Application\Barcode\Result;

use App\Application\Barcode\Result\Enum\Status;

class BarcodeInfoNotFound implements BarcodeInfoInterface
{
    public function __construct(
        private readonly string $message,
    ) {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getStatus(): Status
    {
        return Status::NotFound;
    }
}
