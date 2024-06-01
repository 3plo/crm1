<?php
/**
 * Created by PhpStorm.
 * Date: 01.06.2024
 * Time: 12:36
 */

namespace App\Application\Barcode\Result;

use App\Application\Barcode\Result\Enum\Status;

interface BarcodeInfoInterface
{
    public function getMessage(): string;

    public function getStatus(): Status;
}
