<?php
/**
 * Created by PhpStorm.
 * Date: 01.06.2024
 * Time: 12:22
 */

namespace App\Application\Barcode\Result\Enum;

enum Status: string
{
    case NotFound = 'not_found';
    case NotAvailable = 'not_available';
    case Active = 'active';
}

