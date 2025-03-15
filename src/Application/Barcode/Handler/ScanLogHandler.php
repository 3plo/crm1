<?php
/**
 * Created by PhpStorm.
 * Date: 29.02.2024
 * Time: 21:54
 */

namespace App\Application\Barcode\Handler;

use App\Application\Barcode\Command\ScanLogCommand;
use App\Domain\Barcode\ScanLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class ScanLogHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TranslatorInterface    $translator,
    ) {
    }

    public function handle(ScanLogCommand $command): void
    {
        $barcodeHandleResult = $command->getBarcodeHandleResult();
        $isInDelta = $barcodeHandleResult->isInDelta();

        $log = (new ScanLog())
            ->setBarcodeString($command->getBarcodeString())
            ->setLocation($command->getLocation())
            ->setBarcode($command->getBarcode())
            ->setStatus(
                true === $isInDelta
                    ? false :
                    $barcodeHandleResult->getStatus(),
            )
            ->setMessage(
                true === $isInDelta
                    ? $this->translator->trans('barcode_scan_double_check_title')
                    : $barcodeHandleResult->getMessage()
            );

        $this->entityManager->persist($log);

        if (
            (null !== $card = $command->getBarcode()?->getCard()) &&
            true === $command->getBarcodeHandleResult()->getStatus() &&
            (
                false === $isInDelta ||
                null === $isInDelta
            )
        ) {
            $card
                ->setCountUsage($card->getCountUsage() + 1)
                ->setLastUsage(new \DateTimeImmutable());
        }

        $this->entityManager->flush();
    }
}
