<?php
/**
 * Created by PhpStorm.
 * Date: 02.03.2024
 * Time: 21:57
 */

namespace App\Domain\Barcode\Command;

use App\Domain\Card\Card;

class CreateCommand
{
    public function __construct(
        private readonly Card $card,
    ) {
    }

    public function getCard(): Card
    {
        return $this->card;
    }
}
