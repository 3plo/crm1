<?php
/**
 * Created by PhpStorm.
 * Date: 04.02.2024
 * Time: 22:35
 */

namespace App\View\Scheme\Factory;

use App\View\Request\StructureValidatedRequestInterface;
use App\View\Request\StructureWithParamsValidatedRequestInterface;
use Symfony\Component\Validator\Constraint;

class SchemeFactory
{
    /**
     * @param mixed[] $requestData
     * @return Constraint[]
     */
    public function createRequestScheme(string $argumentType, array $requestData): array
    {
        if (false === is_a($argumentType, StructureValidatedRequestInterface::class, true)) {
            /** @var StructureValidatedRequestInterface $argumentType */
            return $argumentType::getStructure();
        }

        /** @var StructureWithParamsValidatedRequestInterface $argumentType */
        return $argumentType::getStructure($requestData);
    }
}
