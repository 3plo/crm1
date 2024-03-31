<?php
/**
 * Created by PhpStorm.
 * Date: 04.02.2024
 * Time: 22:55
 */

namespace App\Infrastructure\Exception;

use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class FormValidationException extends \Exception
{
    public function __construct(
        private readonly FormErrorIterator $errorList
    ) {
        parent::__construct('Validation exception');
    }

    /**
     * @return string[]
     */
    public function toArray(): array//TODO
    {
//        $errorList = [];
//        foreach ($this->errorList as $error) {
//            $errorList[] = [
//                'property' => $error->getPropertyPath(),
//                'message' => $error->getMessage(),
//            ];
//        }
//
//        return $errorList;
        dd($this->errorList);
        return [];
    }
}
