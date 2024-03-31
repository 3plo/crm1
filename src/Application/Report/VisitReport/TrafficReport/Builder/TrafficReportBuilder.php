<?php
/**
 * Created by PhpStorm.
 * Date: 31.03.2024
 * Time: 15:35
 */

namespace App\Application\Report\VisitReport\TrafficReport\Builder;

use App\Application\Report\VisitReport\TrafficReport\Command\ReportFilterCommand;
use App\Application\Report\VisitReport\TrafficReport\Result\Item;
use App\Domain\Barcode\Repository\ScanLogRepository;

class TrafficReportBuilder
{
    public function __construct(
        private readonly ScanLogRepository $scanLogRepository,
    ) {
    }

    /**
     * @return Item[]
     */
    public function build(ReportFilterCommand $command): array
    {
        $scanLogAggregateList = $this->scanLogRepository->findByIntervalAggregatedByHours(
            $command->getDateFrom(),
            $command->getDateTill(),
            $command->getLocationId(),
            $command->getProductId(),
        );

        $result = [];
        foreach ($scanLogAggregateList as $item) {
            $title = $item['title'] ?? '';
            $result[$title] = new Item(
                $title,
                (int) $item['countSuccess'],
            );
        }

        ksort($scanLogAggregateList);

        return $result;
    }
}
