<?php
/**
 * Created by PhpStorm.
 * Date: 07.04.2024
 * Time: 11:38
 */

namespace App\View\Request\Admin\UserControl;

use App\View\Request\StructureValidatedRequestInterface;
use App\View\Scheme\Admin\UserControl\ToggleUserScheme;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraint;

class ToggleUserRequest implements StructureValidatedRequestInterface
{
    #[JMS\SerializedName('userId')]
    private string $userId;

    private bool $enabled;

    public function getUserId(): string
    {
        return $this->userId;
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
        return ToggleUserScheme::getSchemeConstraintList();
    }
}
