<?php declare(strict_types=1);

namespace App\View\RequestResolver;

use App\Infrastructure\Exception\ValidationException;
use App\View\Request\StructureValidatedRequestInterface;
use App\View\Scheme\Factory\SchemeFactory;
use JMS\Serializer\ArrayTransformerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRequestResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly ArrayTransformerInterface $transformer,
        private readonly ValidatorInterface        $validator,
        private readonly SchemeFactory             $schemeFactory,
    ) {
    }

    /**
     * @param Request $request
     * @return bool
     */
    abstract protected function canProcess(Request $request): bool;

    /**
     * @param Request $request
     * @return mixed[]
     */
    abstract protected function getRequestData(Request $request): array;

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return mixed[]
     * @throws \JsonException
     * @throws ValidationException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        /** @var class-string|StructureValidatedRequestInterface $argumentType */
        $argumentType = (string) $argument->getType();
        if (false === $this->isSupport($request, $argumentType)) {
            return [];
        }

        if (false === is_a($argumentType, StructureValidatedRequestInterface::class, true)) {
            return [$request];
        }

        $requestData = $this->getRequestData($request);
        $constraints = new Collection($this->schemeFactory->createRequestScheme($argumentType, $requestData));
        $this->validate($requestData, $constraints);
        $controllerRequest = $this->transformer->fromArray($requestData, $argumentType);
        $this->validate($controllerRequest);

        return [$controllerRequest];
    }

    /**
     * @param Request $request
     * @param string $argumentType
     * @return bool
     */
    private function isSupport(Request $request, string $argumentType): bool
    {
        if (true === is_a($argumentType, AuthenticationUtils::class, true)) {
            return false;
        }

        if ('' === $argumentType || false === $this->canProcess($request)) {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $value
     * @param Constraint|array|null $constraints
     * @throws ValidationException
     */
    private function validate(mixed $value, Constraint|array|null $constraints = null): void
    {
        $violations = $this->validator->validate($value, $constraints);
        if (0 === $violations->count()) {
            return;
        }

        throw new ValidationException($violations);
    }
}
