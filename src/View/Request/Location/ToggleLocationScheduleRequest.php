<?php
/**
 * Created by PhpStorm.
 * Date: 07.04.2024
 * Time: 11:38
 */

namespace App\View\Request\Location;

use App\View\Request\StructureValidatedRequestInterface;
use App\View\Scheme\Location\ToggleLocationScheduleScheme;
use App\View\Scheme\Location\ToggleLocationScheme;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraint;

class ToggleLocationScheduleRequest implements StructureValidatedRequestInterface
{
    #[JMS\SerializedName('schedulerId')]
    private string $schedulerId;

    private string $type;

    private bool $enabled;

    public function getSchedulerId(): string
    {
        return $this->schedulerId;
    }

    public function getType(): string
    {
        return $this->type;
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
        return ToggleLocationScheduleScheme::getSchemeConstraintList();
    }
}
