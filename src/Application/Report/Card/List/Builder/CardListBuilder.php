<?php
/**
 * Created by PhpStorm.
 * Date: 15.03.2025
 * Time: 0:21
 */

namespace App\Application\Report\Card\List\Builder;

use App\Application\Report\Card\List\Result\Item;
use App\Domain\Card\Repository\CardRepository;
use App\Domain\User\Enum\Role;
use App\Domain\User\User;
use App\Infrastructure\Pagination\Service\PaginationService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class CardListBuilder
{
    private const int MIN_LENGTH_TO_ESCAPE = 6;

    public function __construct(
        private CardRepository $cardRepository,
        private PaginationService $paginationService,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    /**
     * @return Item[]
     */
    public function build(int $page = 1): array
    {
        /*** @var User $user */
        $user = $this->tokenStorage->getToken()?->getUser();

        $dataList = $this->cardRepository->findCardList(
            userId: true === in_array(Role::RoleAdmin->value, $user->getRoles(), true)
                ? null
                : $user->getId(),
            offset: $this->paginationService->calculateOffset($page),
        );

        $result = [];
        foreach ($dataList as $itemData) {
            $result[] = new Item(
                $itemData['id'],
                $this->prepareBarcode($itemData['barcode']),
                $itemData['priceTitle'],
                $itemData['productTitle'],
                $itemData['validFrom'],
                $itemData['validTill'],
                $itemData['createdAt'],
                sprintf('%s %s', $itemData['userFirstName'] ?? '', $itemData['userLastName'] ?? ''),
            );
        }

        return $result;
    }

    private function prepareBarcode(null|string $barcode): string
    {
        if (null === $barcode) {
            return '';
        }

        if (strlen($barcode) <= self::MIN_LENGTH_TO_ESCAPE) {
            return str_repeat('*', self::MIN_LENGTH_TO_ESCAPE);
        }

        return
            sprintf(
                '%s%s%s',
                substr($barcode, 0, 3),
                str_repeat('*', strlen($barcode) - 6),
                substr($barcode, -3, 3),
            );
    }
}
