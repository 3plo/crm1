<?php
/**
 * Created by PhpStorm.
 * Date: 27.05.2024
 * Time: 20:59
 */

namespace App\Infrastructure\Services;

use Symfony\Contracts\Translation\TranslatorInterface;

class TranslateService
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * @param string[] $list
     * @return string[]
     */
    public function translateList(array $list, string $prefix = '', string $postfix = ''): array
    {
        $result = [];
        foreach ($list as $key => $item) {
            $result[$key] = $this->translator->trans(sprintf('%s%s%s', $prefix, $item, $postfix));
        }

        return $result;
    }

    /**
     * @param string[] $list
     * @return string[]
     */
    public function translateOptions(array $list, string $prefix = '', string $postfix = ''): array
    {
        $result = [];
        foreach ($list as $key => $item) {
            $result[$this->translator->trans(sprintf('%s%s%s', $prefix, $item, $postfix))] = $item;
        }

        return $result;
    }
}
