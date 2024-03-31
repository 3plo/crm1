<?php
/**
 * Created by PhpStorm.
 * Date: 11.02.2024
 * Time: 19:43
 */

namespace App\View\RequestResolver;

use App\Infrastructure\Exception\FormValidationException;
use App\Infrastructure\Exception\ValidationException;
use App\View\Form\Types\AbstractRequestType;
use App\View\Request\FormRequestInterface;
use JMS\Serializer\ArrayTransformerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class FormRequestResolver
{
    public function __construct(
        private readonly ArrayTransformerInterface $arrayTransformer,
        private readonly FormFactoryInterface $formFactory,
    ) {
    }

    public function resolve(Request $request, string $formType): null|FormRequestInterface
    {
        /** @var AbstractRequestType $formType */
        if (false === is_a($formType, AbstractRequestType::class, true)) {
            throw new \RuntimeException();//TODO add message
        }

        $form = $this->formFactory->create($formType);
        $form->handleRequest($request);

        if (false === $form->isSubmitted()) {
            return null;
        }

        if (false === $form->isValid()) {
            throw new FormValidationException($form->getErrors(true));//TODO use other exception
        }

        return $this->arrayTransformer->fromArray($this->prepareData($form->getData()), $formType::getRequestClass());
    }

    private function prepareData(array $data): array//TODO make extension for serializer
    {
        foreach ($data as $index => $value) {
            if (true === is_array($value)) {
                $data[$index] = $this->prepareData($value);
            }

            if (true === $value instanceof \DateTimeInterface) {
                $data[$index] = $value->format(\DateTimeInterface::ATOM);
            }
        }

        return $data;
    }
}
