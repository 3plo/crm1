<?php
/**
 * Created by PhpStorm.
 * Date: 12.02.2024
 * Time: 21:48
 */

namespace App\View\Request\Location;

use App\View\Request\FormRequestInterface;

class LocationCreateRequest implements FormRequestInterface
{
    private bool $enabled;

    private string $title;

    private string $description;

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
