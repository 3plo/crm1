<?php
/**
 * Created by PhpStorm.
 * Date: 31.03.2024
 * Time: 15:33
 */

namespace App\Application\Report\VisitReport\TrafficReport\Result;

class Item
{
    public function __construct(
        private readonly string $title,
        private readonly int $value,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
