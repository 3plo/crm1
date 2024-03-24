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

class ScanLogHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ScanLogCommand $command): void
    {
        $log = (new ScanLog())
            ->setBarcodeString($command->getBarcodeString())
            ->setLocation($command->getLocation())
            ->setBarcode($command->getBarcode())
            ->setStatus($command->getBarcodeHandleResult()->getStatus())
            ->setMessage($command->getBarcodeHandleResult()->getMessage());

        $this->entityManager->persist($log);

        if (
            (null !== $card = $command->getBarcode()?->getCard()) &&
            true === $command->getBarcodeHandleResult()->getStatus()
        ) {
            $card
                ->setCountUsage($card->getCountUsage() + 1)
                ->setLastUsage(new \DateTimeImmutable());
        }

        $this->entityManager->flush();
    }
}
