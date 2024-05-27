<?php
/**
 * Created by PhpStorm.
 * Date: 19.05.2024
 * Time: 14:24
 */

namespace App\Infrastructure\Twig\Extension;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateExtension extends AbstractExtension
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('day_name', [$this, 'getDayName']),
        ];
    }

    public function getDayName($dayNumber)
    {
        $days = [
            1 => $this->translator->trans('day_number_monday'),
            2 => $this->translator->trans('day_number_tuesday'),
            3 => $this->translator->trans('day_number_wednesday'),
            4 => $this->translator->trans('day_number_thursday'),
            5 => $this->translator->trans('day_number_friday'),
            6 => $this->translator->trans('day_number_saturday'),
            7 => $this->translator->trans('day_number_sunday'),
        ];

        return $days[$dayNumber] ?? 'Invalid day';
    }
}
