<?php declare(strict_types=1);

namespace App\View\RequestResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PostJsonRequestRequestResolver extends AbstractRequestResolver
{
    private const REQUEST_METHOD = 'POST';

    private const DATA_JSON_FORMAT = 'json';

    protected function canProcess(Request $request): bool
    {
        if (self::DATA_JSON_FORMAT !== $request->getContentTypeFormat()) {
            throw new BadRequestHttpException(sprintf('Content format must be %s', self::DATA_JSON_FORMAT));
        }

        return '' !== (string) $request->getContent() && self::REQUEST_METHOD === $request->getMethod();
    }

    /**
     * @param Request $request
     * @return mixed[]
     * @throws \JsonException
     */
    protected function getRequestData(Request $request): array
    {
        return json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }
}
