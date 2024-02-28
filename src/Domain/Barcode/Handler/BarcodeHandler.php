<?php
/**
 * Created by PhpStorm.
 * Date: 26.02.2024
 * Time: 21:06
 */

namespace App\Domain\Barcode\Handler;

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
    ) {
    }

    public function handle(
        string $barcode,
        string $locationId,
    ): BarcodeHandleResult {
        $location = $this->locationRepository->find($locationId);
        $barcodeData = $this->barcodeRepository->findOneBy(['barcode' => $barcode]);

        if (null === $barcodeData) {
            return new BarcodeHandleResult(false, 'Barcode not found');
        }

        if (false === $barcodeData->getProduct()->hasLocation($location)) {
            return new BarcodeHandleResult(false, 'No corresponding for current location');
        }
        //TODO check card state
        //TODO add scan log
        //TODO change count usage for card

        return new BarcodeHandleResult(true, 'allow');
    }
}
