<?php
/**
 * Created by PhpStorm.
 * Date: 04.02.2024
 * Time: 22:45
 */

namespace App\View\Request;

use Symfony\Component\Validator\Constraint;

interface StructureValidatedRequestInterface
{
    /**
     * @return Constraint[]
     */
    public static function getStructure(): array;
}
