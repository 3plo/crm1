<?php
/**
 * Created by PhpStorm.
 * Date: 26.02.2024
 * Time: 22:12
 */

namespace App\View\Form\Constraint\Product;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ProductExist extends Constraint
{
    public function getMessage(): string
    {
        return 'Product not exist';
    }
}
