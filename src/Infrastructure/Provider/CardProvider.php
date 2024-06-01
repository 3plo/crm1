<?php
/**
 * Created by PhpStorm.
 * Date: 01.06.2024
 * Time: 20:36
 */

namespace App\Infrastructure\Provider;

use App\Domain\Card\Card;

class CardProvider
{
    private Card $card;

    public function getCard(): Card
    {
        return $this->card;
    }

    public function setCard(Card $card): self
    {
        $this->card = $card;
        return $this;
    }
}
