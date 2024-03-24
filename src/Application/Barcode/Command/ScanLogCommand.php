<?php
/**
 * Created by PhpStorm.
 * Date: 29.02.2024
 * Time: 21:54
 */

namespace App\Application\Barcode\Command;

use App\Application\Barcode\Result\BarcodeHandleResult;
use App\Domain\Barcode\Barcode;
use App\Domain\Location\Location;

class ScanLogCommand
{
    public function __construct(
        private readonly BarcodeHandleResult $barcodeHandleResult,
        private readonly Location $location,
        private readonly null|Barcode $barcode,
        private readonly string $barcodeString,
    ) {
    }

    public function getBarcodeHandleResult(): BarcodeHandleResult
    {
        return $this->barcodeHandleResult;
    }

    public function getLocation(): Location
    {
        return $this->location;
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
