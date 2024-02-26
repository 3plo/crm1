<?php
/**
 * Created by PhpStorm.
 * Date: 26.02.2024
 * Time: 22:02
 */

namespace App\View\Request\Barcode;

use App\View\Request\StructureValidatedRequestInterface;
use App\View\Scheme\Barcode\FindRequestScheme;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraint;

class FindRequest implements StructureValidatedRequestInterface
{
    #[JMS\SerializedName('productId')]
    private string $productId;

    private string $barcode;

    /**
     * @return Constraint[]
     */
    #[\Override] public static function getStructure(): array
    {
        return FindRequestScheme::getSchemeConstraintList();
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getBarcode(): string
    {
        return $this->barcode;
    }
}
