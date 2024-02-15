<?php declare(strict_types=1);

namespace App\View\RequestResolver;

use Symfony\Component\HttpFoundation\Request;

class GetRequestRequestResolver extends AbstractRequestResolver
{
    private const REQUEST_METHOD = 'GET';

    protected function canProcess(Request $request): bool
    {
        return self::REQUEST_METHOD === $request->getMethod();
    }

    protected function getRequestData(Request $request): array
    {
        return $request->query->all();
    }
}
