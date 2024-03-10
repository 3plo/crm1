<?php
/**
 * Created by PhpStorm.
 * Date: 29.02.2024
 * Time: 21:54
 */

namespace App\Domain\Barcode\Command;

use App\Domain\Barcode\Barcode;
use App\Domain\Barcode\Result\BarcodeHandleResult;

class ScanLogCommand
{
    public function __construct(
        private readonly BarcodeHandleResult $barcodeHandleResult,
        private readonly null|Barcode $barcode,
        private readonly string $barcodeString,
    ) {
    }

    public function getBarcodeHandleResult(): BarcodeHandleResult
    {
        return $this->barcodeHandleResult;
    }

    public function getBarcode(): null|Barcode
    {
        return $this->barcode;
    }

    public function getBarcodeString(): string
    {
        return $this->barcodeString;
    }
}
