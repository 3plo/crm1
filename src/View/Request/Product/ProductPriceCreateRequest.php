<?php
/**
 * Created by PhpStorm.
 * Date: 04.02.2024
 * Time: 23:13
 */

namespace App\View\Request\Product;

use App\View\Request\StructureValidatedRequestInterface;
use App\View\Scheme\Product\ProductPriceCreateScheme;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraint;

class ProductPriceCreateRequest implements StructureValidatedRequestInterface
{
    private bool $enabled;

    #[JMS\SerializedName('productId')]
    private string $productId;

    #[JMS\SerializedName('title')]
    private string $title;

    #[JMS\SerializedName('amountInUAH')]
    private string $amountInUAH;

    /**
     * @return Constraint[]
     */
    #[\Override] public static function getStructure(): array
    {
        return ProductPriceCreateScheme::getSchemeConstraintList();
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getAmountInUAH(): string
    {
        return $this->amountInUAH;
    }
}
