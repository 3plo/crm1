<?php
/**
 * Created by PhpStorm.
 * Date: 26.02.2024
 * Time: 21:06
 */

namespace App\Domain\Barcode\Handler;

use App\Domain\Barcode\Barcode;
use App\Domain\Barcode\Command\ScanLogCommand;
use App\Domain\Barcode\Repository\BarcodeRepository;
use App\Domain\Barcode\Result\BarcodeHandleResult;
use App\Domain\Location\Repository\LocationRepository;
use Symfony\Bundle\SecurityBundle\Security;

class BarcodeHandler
{
    public function __construct(
        private readonly BarcodeRepository  $barcodeRepository,
        private readonly LocationRepository $locationRepository,
        private readonly Security           $security,
        private readonly ScanLogHandler     $scanLogHandler,
    ) {
    }

    public function handle(
        string $barcode,
        string $locationId,
    ): BarcodeHandleResult {
        $location = $this->locationRepository->find($locationId);
        $barcodeData = $this->barcodeRepository->findOneBy(['barcode' => $barcode]);

        if (null === $barcodeData) {
            return $this->makeResult(false, 'Barcode not found', $barcode, $barcodeData);
        }

        if (false === $barcodeData->getProduct()->hasLocation($location)) {
            return $this->makeResult(false, 'No corresponding for current location', $barcode, $barcodeData);
        }

        $card = $barcodeData->getCard();
        if (false === $card->isEnabled()) {
            return $this->makeResult(false, 'Card inactive', $barcode, $barcodeData);
        }

        $card = $barcodeData->getCard();
        if ((new \DateTimeImmutable())->format('Y-m-d') > $card->getValidFrom()->format('Y-m-d')) {
            return $this->makeResult(false, 'Card expired', $barcode, $barcodeData);
        }

        if (
            null !== $card->getLastUsage() &&
            $card->getMaxUsage() >= $card->getCountUsage() &&
            (new \DateTimeImmutable())->format('Y-m-d') !== $card->getLastUsage()->format('Y-m-d')
        ) {
            return $this->makeResult(false, 'Card used max times', $barcode, $barcodeData);
        }

        return $this->makeResult(true, 'allow', $barcode, $barcodeData);
    }

    private function makeResult(
        bool $status,
        string $message,//TODO maybe change status by enum
        string $barcodeValue,
        null|Barcode $barcode,
    ): BarcodeHandleResult {
        $result = new BarcodeHandleResult($status, $message);

        $this->scanLogHandler->handle(
            new ScanLogCommand(
                $result,
                $barcode,
                $barcodeValue,
            ),
        );

        return $result;
    }
}
