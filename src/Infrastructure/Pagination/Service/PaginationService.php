<?php
/**
 * Created by PhpStorm.
 * Date: 15.03.2025
 * Time: 0:34
 */

namespace App\Infrastructure\Pagination\Service;

readonly class PaginationService
{
    public const int DEFAULT_LIMIT = 100;

    public function calculateOffset(int $page = 1, int $limit = self::DEFAULT_LIMIT): int
    {
        return $limit * ($page - 1);
    }
}
