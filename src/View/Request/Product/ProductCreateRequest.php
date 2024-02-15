<?php
/**
 * Created by PhpStorm.
 * Date: 11.02.2024
 * Time: 19:51
 */

namespace App\View\Request\Product;

use App\View\Request\FormRequestInterface;
use JMS\Serializer\Annotation as JMS;

class ProductCreateRequest implements FormRequestInterface
{
    #[JMS\SerializedName('durationDays')]
    private string $durationDays;

    private string $title;

    private string $description;

    public function getDurationDays(): string
    {
        return $this->durationDays;
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
