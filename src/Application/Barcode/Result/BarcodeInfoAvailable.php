<?php
/**
 * Created by PhpStorm.
 * Date: 01.06.2024
 * Time: 12:40
 */

namespace App\Application\Barcode\Result;

use App\Application\Barcode\Result\Enum\Status;

class BarcodeInfoAvailable extends AbstractBarcodeInfo
{
    #[\Override] public function getStatus(): Status
    {
        return Status::Active;
    }
}
