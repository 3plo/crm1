<?php
/**
 * Created by PhpStorm.
 * Date: 02.03.2024
 * Time: 21:57
 */

namespace App\Application\Barcode\Command;

use App\Domain\Card\Card;

readonly class CreateCommand
{
    public function __construct(
        private Card $card,
    ) {
    }

    public function getCard(): Card
    {
        return $this->card;
    }
}
