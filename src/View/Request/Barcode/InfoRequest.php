<?php
/**
 * Created by PhpStorm.
 * Date: 26.02.2024
 * Time: 22:02
 */

namespace App\View\Request\Barcode;

use App\View\Request\StructureValidatedRequestInterface;
use App\View\Scheme\Barcode\InfoRequestScheme;
use Symfony\Component\Validator\Constraint;

class InfoRequest implements StructureValidatedRequestInterface
{
    private string $barcode;

    /**
     * @return Constraint[]
     */
    #[\Override] public static function getStructure(): array
    {
        return InfoRequestScheme::getSchemeConstraintList();
    }

    public function getBarcode(): string
    {
        return $this->barcode;
    }
}
