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
use Symfony\Contracts\Translation\TranslatorInterface;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

readonly class BarcodeCheckHandler
{
    public function __construct(
        private TranslatorInterface $translator,
        private BarcodeRepository   $barcodeRepository,
        private LocationRepository  $locationRepository,
        private ScanLogHandler      $scanLogHandler,
    ) {
    }

    public function handle(
        string $barcode,
        string $locationId,
    ): BarcodeHandleResult {
        $location = $this->locationRepository->find($locationId);
        $barcodeData = $this->barcodeRepository->findOneBy(['barcode' => $barcode]);

        if (null === $barcodeData) {
            return $this->makeResult(
                false,
                $this->translator->trans('barcode_scan_status_barcode_not_found_title'),
                $barcode,
                $location,
                null,
            );
        }

        if (false === $barcodeData->getProduct()->hasLocation($location)) {
            return $this->makeResult(
                false,
                $this->translator->trans('barcode_scan_status_not_corresponding_for_location_title'),
                $barcode,
                $location,
                $barcodeData,
            );
        }

        $card = $barcodeData->getCard();
        if (false === $barcodeData->isEnabled() || false === $card->isEnabled()) {
            return $this->makeResult(
                false,
                $this->translator->trans('barcode_scan_status_card_inactive_title'),
                $barcode,
                $location,
                $barcodeData,
            );
        }

        $card = $barcodeData->getCard();
        if ((new \DateTimeImmutable())->format('Y-m-d') > $card->getValidTill()->format('Y-m-d')) {
            return $this->makeResult(
                false,
                $this->translator->trans('barcode_scan_status_card_expired_title'),
                $barcode,
                $location,
                $barcodeData,
            );
        }

        //TODO add to database configuration;
        $isInDelta = (new \DateTimeImmutable())->format('Y-m-d') === $card->getLastUsage()?->format('Y-m-d');
        if (
            null !== $card->getLastUsage() &&
            $card->getCountUsage() >= $card->getMaxUsage() &&
            false === $isInDelta
        ) {
            return $this->makeResult(
                false,
                $this->translator->trans('barcode_scan_status_card_used_max_times_title'),
                $barcode,
                $location,
                $barcodeData,
            );
        }

        return $this->makeResult(
            true,
            $this->translator->trans('barcode_scan_status_allow_title'),
            $barcode,
            $location,
            $barcodeData,
            $isInDelta,
        );
    }

    private function makeResult(
        bool         $status,
        string       $message,//TODO maybe change status by enum
        string       $barcodeValue,
        Location     $location,
        null|Barcode $barcode,
        null|bool    $isInDelta = null,
    ): BarcodeHandleResult {
        $result = new BarcodeHandleResult($status, $message, $isInDelta);
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
