<?php
/**
 * Created by PhpStorm.
 * Date: 13.03.2024
 * Time: 19:58
 */

namespace App\Application\Report\VisitReport\GeneralReport\Builder;

use App\Application\Report\VisitReport\GeneralReport\Command\ReportFilterCommand;
use App\Application\Report\VisitReport\GeneralReport\Result\Item;
use App\Domain\Barcode\Repository\ScanLogRepository;
use App\Domain\Location\Repository\LocationRepository;
use App\Domain\Product\Repository\ProductRepository;

class GeneralReportBuilder
{
    public function __construct(
        private readonly ScanLogRepository $scanLogRepository,
    ) {
    }

    public function build(ReportFilterCommand $command): array
    {
        $scanLogAggregateList = $this->scanLogRepository->findByInterval(
            $command->getDateFrom(),
            $command->getDateTill(),
            $command->getLocationId(),
            $command->getProductId(),
        );

        $result = [];
        foreach ($scanLogAggregateList as $item) {
            $result[] = new Item(
                $item['locationTitle'] ?? '',
                $item['productTitle'] ?? '',
                (int) $item['countSuccess'],
                (int) $item['countDecline'],
            );
        }

        return $result;
    }
}
