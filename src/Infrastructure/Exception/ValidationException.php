<?php
/**
 * Created by PhpStorm.
 * Date: 04.02.2024
 * Time: 22:55
 */

namespace App\Infrastructure\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \Exception
{
    public function __construct(
        private readonly ConstraintViolationListInterface $errorList,
    ) {
        parent::__construct('Validation exception');
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        $errorList = [];
        foreach ($this->errorList as $error) {
            $errorList[] = [
                'property' => $error->getPropertyPath(),
                'message' => $error->getMessage(),
            ];
        }

        return $errorList;
    }
}
