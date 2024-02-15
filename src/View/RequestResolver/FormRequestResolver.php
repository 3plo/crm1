<?php
/**
 * Created by PhpStorm.
 * Date: 11.02.2024
 * Time: 19:43
 */

namespace App\View\RequestResolver;

use App\Infrastructure\Exception\ValidationException;
use App\View\Form\AbstractRequestType;
use App\View\Request\FormRequestInterface;
use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\SerializerInterface;
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
            throw new ValidationException($form->getErrors(true));//TODO use other exception
        }

        return $this->arrayTransformer->fromArray($form->getData(), $formType::getRequestClass());
    }
}
