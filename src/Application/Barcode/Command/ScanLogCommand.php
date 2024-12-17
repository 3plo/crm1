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

readonly class ScanLogCommand
{
    public function __construct(
        private BarcodeHandleResult $barcodeHandleResult,
        private Location            $location,
        private null|Barcode        $barcode,
        private string              $barcodeString,
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
