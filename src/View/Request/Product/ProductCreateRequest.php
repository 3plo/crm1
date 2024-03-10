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

    #[JMS\SerializedName('countUsage')]
    private string $countUsage;

    private string $type;

    private string $title;

    private string $description;

    public function getDurationDays(): string
    {
        return $this->durationDays;
    }

    public function getCountUsage(): string
    {
        return $this->countUsage;
    }

    public function getType(): string
    {
        return $this->type;
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
