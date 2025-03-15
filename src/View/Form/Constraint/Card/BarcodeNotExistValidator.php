<?php
/**
 * Created by PhpStorm.
 * Date: 26.02.2024
 * Time: 22:12
 */

namespace App\View\Form\Constraint\Card;

use App\Domain\Barcode\Repository\BarcodeRepository;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class BarcodeNotExistValidator extends ConstraintValidator
{
    public function __construct(
        private readonly BarcodeRepository $barcodeRepository,
    ) {
    }

    #[\Override] public function validate(mixed $value, Constraint $constraint): void
    {
        if (false === $constraint instanceof BarcodeNotExist) {
            throw new UnexpectedTypeException($constraint, BarcodeNotExist::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $barcode = $this->barcodeRepository->findOneBy(['barcode' => $value]);
        if (null === $barcode) {
            return;
        }

        $this->context->buildViolation($constraint->getMessage())
            ->setParameter('[barcode]', $value)
            ->addViolation();
    }
}
