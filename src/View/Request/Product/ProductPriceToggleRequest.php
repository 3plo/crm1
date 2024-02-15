<?php
/**
 * Created by PhpStorm.
 * Date: 04.02.2024
 * Time: 23:13
 */

namespace App\View\Request\Product;

use App\View\Request\StructureValidatedRequestInterface;
use App\View\Scheme\Product\ProductPriceToggleScheme;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraint;

class ProductPriceToggleRequest implements StructureValidatedRequestInterface
{
    private bool $enabled;

    #[JMS\SerializedName('productId')]
    private string $productId;

    #[JMS\SerializedName('priceId')]
    private string $priceId;

    /**
     * @return Constraint[]
     */
    #[\Override] public static function getStructure(): array
    {
        return ProductPriceToggleScheme::getSchemeConstraintList();
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getPriceId(): string
    {
        return $this->priceId;
    }
}
