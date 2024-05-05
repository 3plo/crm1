<?php
/**
 * Created by PhpStorm.
 * Date: 26.02.2024
 * Time: 22:02
 */

namespace App\View\Request\Barcode;

use App\View\Form\Constraint\Location\LocationExist;
use App\View\Request\LocationAccessInterface;
use App\View\Request\StructureValidatedRequestInterface;
use App\View\Scheme\Barcode\FindRequestScheme;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraint;

class FindRequest implements StructureValidatedRequestInterface, LocationAccessInterface
{
    #[JMS\SerializedName('locationId')]
    #[LocationExist]
    private string $locationId;

    private string $barcode;

    /**
     * @return Constraint[]
     */
    #[\Override] public static function getStructure(): array
    {
        return FindRequestScheme::getSchemeConstraintList();
    }

    public function getLocationId(): string
    {
        return $this->locationId;
    }

    #[\Override] public function getLocationList(): array
    {
        return [$this->locationId];
    }

    public function getBarcode(): string
    {
        return $this->barcode;
    }
}
