<?php
/**
 * Created by PhpStorm.
 * Date: 28.02.2024
 * Time: 21:43
 */

namespace App\View\Form\Constraint\Location;

use App\Domain\Location\Repository\LocationRepository;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class LocationExistValidator extends ConstraintValidator
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
    ) {
    }

    #[\Override] public function validate(mixed $value, Constraint $constraint): void
    {
        if (false === $constraint instanceof LocationExist) {
            throw new UnexpectedTypeException($constraint, LocationExist::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (false === is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $location = $this->locationRepository->find($value);
        if (null !== $location) {
            return;
        }

        $this->context->buildViolation($constraint->getMessage())
            ->setParameter('[locationId]', $value)
            ->addViolation();
    }
}
