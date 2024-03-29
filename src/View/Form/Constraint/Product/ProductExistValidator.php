<?php
/**
 * Created by PhpStorm.
 * Date: 26.02.2024
 * Time: 22:12
 */

namespace App\View\Form\Constraint\Product;

use App\Domain\Product\Repository\ProductRepository;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ProductExistValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {
    }

    #[\Override] public function validate(mixed $value, Constraint $constraint): void
    {
        if (false === $constraint instanceof ProductExist) {
            throw new UnexpectedTypeException($constraint, ProductExist::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $product = $this->productRepository->find($value);
        if (null !== $product) {
            return;
        }

        $this->context->buildViolation($constraint->getMessage())
            ->setParameter('[productId]', $value)
            ->addViolation();
    }
}
