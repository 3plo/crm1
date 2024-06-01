<?php
/**
 * Created by PhpStorm.
 * Date: 01.06.2024
 * Time: 12:14
 */

namespace App\Application\Barcode\Handler;

use App\Application\Barcode\Result\BarcodeInfoAvailable;
use App\Application\Barcode\Result\BarcodeInfoInterface;
use App\Application\Barcode\Result\BarcodeInfoNotAvailable;
use App\Application\Barcode\Result\BarcodeInfoNotFound;
use App\Domain\Barcode\Barcode;
use App\Domain\Barcode\Repository\BarcodeRepository;
use App\Domain\Card\Card;
use Symfony\Contracts\Translation\TranslatorInterface;

class BarcodeInfoHandler
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly BarcodeRepository   $barcodeRepository,
    ) {
    }

    public function handle(string $barcode): BarcodeInfoInterface
    {
        $barcode = $this->barcodeRepository->findOneBy(['barcode' => $barcode]);
        if (null === $barcode) {
            return new BarcodeInfoNotFound(
                $this->translator->trans('barcode_info_not_found_message'),
            );
        }

        $card = $barcode->getCard();
        if (true === $this->isActive($barcode, $card)) {
            return new BarcodeInfoAvailable(
                $this->getMessage($barcode, $card),
                $card->getValidFrom(),
                $card->getValidTill(),
                $card->getCountUsage(),
                $card->getMaxUsage(),
                false,
            );
        }

        return new BarcodeInfoNotAvailable(
            $this->getMessage($barcode, $card),
            $card->getValidFrom(),
            $card->getValidTill(),
            $card->getCountUsage(),
            $card->getMaxUsage(),
            true,
        );
    }

    private function isActive(Barcode $barcode, Card $card): bool
    {
        return
            $this->isBarcodeActive($barcode) &&
            $this->isCardActive($card) &&
            $this->allowExtraUsage($card) &&
            $this->allowValidByTime($card);
    }

    private function getMessage(Barcode $barcode, Card $card): string
    {
        if (false === $this->isBarcodeActive($barcode)) {
            return $this->translator->trans('barcode_info_barcode_inactive_message');
        }

        if (false === $this->isCardActive($card)) {
            return $this->translator->trans('barcode_info_card_inactive_message');
        }

        if (false === $this->allowExtraUsage($card)) {
            return $this->translator->trans('barcode_info_already_used_max_times_message');
        }

        if (false === $this->allowValidByTime($card)) {
            return $this->translator->trans('barcode_info_not_available_at_this_time_message');
        }

        return $this->translator->trans('barcode_info_allow_message');
    }

    private function isBarcodeActive(Barcode $barcode): bool
    {
        return true === $barcode->isEnabled();
    }

    private function isCardActive(Card $card): bool
    {
        return true === $card->isEnabled();
    }

    private function allowExtraUsage(Card $card): bool
    {
        return $card->getMaxUsage() > $card->getCountUsage();
    }

    private function allowValidByTime(Card $card): bool
    {
        $currentDatetime = (new \DateTimeImmutable())->format('Y-m-d');

        return
            $card->getValidTill()->format('Y-m-d') >= $currentDatetime &&
            $currentDatetime >= $card->getValidFrom()->format('Y-m-d');
    }
}
