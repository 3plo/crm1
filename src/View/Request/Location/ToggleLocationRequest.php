<?php
/**
 * Created by PhpStorm.
 * Date: 07.04.2024
 * Time: 11:38
 */

namespace App\View\Request\Location;

use App\View\Request\StructureValidatedRequestInterface;
use App\View\Scheme\Location\ToggleLocationScheme;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraint;

class ToggleLocationRequest implements StructureValidatedRequestInterface
{
    #[JMS\SerializedName('locationId')]
    private string $locationId;

    private bool $enabled;

    public function getLocationId(): string
    {
        return $this->locationId;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return Constraint[]
     */
    #[\Override] public static function getStructure(): array
    {
        return ToggleLocationScheme::getSchemeConstraintList();
    }
}
