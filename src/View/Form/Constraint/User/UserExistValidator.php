<?php
/**
 * Created by PhpStorm.
 * Date: 08.04.2024
 * Time: 23:11
 */

namespace App\View\Form\Constraint\User;

use App\Domain\User\Repository\UserRepository;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UserExistValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    #[\Override] public function validate(mixed $value, Constraint $constraint): void
    {
        if (false === $constraint instanceof UserExist) {
            throw new UnexpectedTypeException($constraint, UserExist::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $user = $this->userRepository->find($value);
        if (null !== $user) {
            return;
        }

        $this->context->buildViolation($constraint->getMessage())
            ->setParameter('[userId]', $value)
            ->addViolation();
    }
}
