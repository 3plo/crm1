<?php
/**
 * Created by PhpStorm.
 * Date: 26.02.2024
 * Time: 21:06
 */

namespace App\Application\Barcode\Handler;

use App\Application\Barcode\Command\ScanLogCommand;
use App\Application\Barcode\Result\BarcodeHandleResult;
use App\Domain\Barcode\Barcode;
use App\Domain\Barcode\Repository\BarcodeRepository;
use App\Domain\Location\Location;
use App\Domain\Location\Repository\LocationRepository;
use Symfony\Bundle\SecurityBundle\Security;

class BarcodeHandler
{
    public function __construct(
        private readonly BarcodeRepository  $barcodeRepository,
        private readonly LocationRepository $locationRepository,
        private readonly Security           $security,
        private readonly ScanLogHandler     $scanLogHandler,
    )
    {
    }

    public function handle(
        string $barcode,
        string $locationId,
    ): BarcodeHandleResult
    {
        $location = $this->locationRepository->find($locationId);
        $barcodeData = $this->barcodeRepository->findOneBy(['barcode' => $barcode]);

        if (null === $barcodeData) {
            return $this->makeResult(
                false,
                'Barcode not found',
                $barcode,
                $location,
                $barcodeData,
            );
        }

        if (false === $barcodeData->getProduct()->hasLocation($location)) {
            return $this->makeResult(
                false,
                'No corresponding for current location',
                $barcode,
                $location,
                $barcodeData,
            );
        }

        $card = $barcodeData->getCard();
        if (false === $card->isEnabled()) {
            return $this->makeResult(
                false,
                'Card inactive',
                $barcode,
                $location,
                $barcodeData,
            );
        }

        $card = $barcodeData->getCard();
        if ((new \DateTimeImmutable())->format('Y-m-d') > $card->getValidFrom()->format('Y-m-d')) {
            return $this->makeResult(
                false,
                'Card expired',
                $barcode,
                $location,
                $barcodeData,
            );
        }

        if (
            null !== $card->getLastUsage() &&
            $card->getMaxUsage() >= $card->getCountUsage() &&
            (new \DateTimeImmutable())->format('Y-m-d') !== $card->getLastUsage()->format('Y-m-d')
        ) {
            return $this->makeResult(
                false,
                'Card used max times',
                $barcode,
                $location,
                $barcodeData,
            );
        }

        return $this->makeResult(
            true,
            'allow',
            $barcode,
            $location,
            $barcodeData,
        );
    }

    private function makeResult(
        bool         $status,
        string       $message,//TODO maybe change status by enum
        string       $barcodeValue,
        Location     $location,
        null|Barcode $barcode,
    ): BarcodeHandleResult
    {
        $result = new BarcodeHandleResult($status, $message);

        $this->scanLogHandler->handle(
            new ScanLogCommand(
                $result,
                $location,
                $barcode,
                $barcodeValue,
            ),
        );

        return $result;
    }
}
