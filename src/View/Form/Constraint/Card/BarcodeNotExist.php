<?php
/**
 * Created by PhpStorm.
 * Date: 08.03.2025
 * Time: 13:29
 */

namespace App\View\Form\Constraint\Card;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class BarcodeNotExist extends Constraint
{
    public function getMessage(): string
    {
        return 'Barcode already exist';
    }
}
