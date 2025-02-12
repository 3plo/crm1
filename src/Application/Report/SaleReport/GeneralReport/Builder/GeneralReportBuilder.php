<?php
/**
 * Created by PhpStorm.
 * Date: 13.03.2024
 * Time: 19:58
 */

namespace App\Application\Report\SaleReport\GeneralReport\Builder;

use App\Application\Report\SaleReport\GeneralReport\Command\ReportFilterCommand;
use App\Application\Report\SaleReport\GeneralReport\Result\Item;
use App\Domain\Card\Repository\CardRepository;

readonly class GeneralReportBuilder
{
    public function __construct(
        private CardRepository $cardRepository,
    ) {
    }

    /**
     * @return Item[]
     */
    public function build(ReportFilterCommand $command): array
    {
        $cardList = $this->cardRepository->findByInterval(
            $command->getDateFrom(),
            $command->getDateTill(),
            $command->getProductId(),
            $command->getUserId(),
        );

        $result = [];
        foreach ($cardList as $item) {
            $result[] = new Item(
                null === $item['userFirstName'] && null === $item['userLastName'] ?
                    null :
                    sprintf('%s %s', $item['userFirstName'] ?? '', $item['userLastName'] ?? ''),
                $item['priceTitle'] ?? '',
                $item['productTitle'] ?? '',
                (int) $item['countCard'],
                (int) $item['sumCard'],
            );
        }

        return $result;
    }
}
