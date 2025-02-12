<?php
/**
 * Created by PhpStorm.
 * Date: 01.06.2024
 * Time: 20:19
 */

namespace App\Domain\Card\Repository;

use App\Domain\Card\Card;
use App\Domain\Product\Price;
use App\Domain\Product\Product;
use App\Domain\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    private const string ALIAS = 'c';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    public function findByInterval(
        \DateTimeImmutable $dateFrom,
        \DateTimeImmutable $dateTill,
        null|string $productId,
        null|string $userId,
    ): array {
        $qb = $this->createQueryBuilder(self::ALIAS);

        $qb
            ->select(
                sprintf('%s.id as productId', 'p'),
                sprintf('%s.title as productTitle', 'p'),
                sprintf('%s.title as priceTitle', 'pr'),
                sprintf('%s.firstName as userFirstName', 'u'),
                sprintf('%s.lastName as userLastName', 'u'),
                sprintf('COUNT(%s.id) AS countCard', self::ALIAS),
                sprintf('SUM(%s.amountInUAH) AS sumCard', 'pr'),
            )
            ->innerJoin(Product::class, 'p', Join::WITH, sprintf('%s.product = %s.id', self::ALIAS, 'p'))
            ->innerJoin(Price::class, 'pr', Join::WITH, sprintf('%s.price = %s.id', self::ALIAS, 'pr'))
            ->innerJoin(User::class, 'u', Join::WITH, sprintf('%s.createdBy = %s.id', self::ALIAS, 'u'))
            ->where($qb->expr()->between(sprintf('%s.createdAt', self::ALIAS), ':startDate', ':endDate'))
            ->setParameter('startDate', $dateFrom)
            ->setParameter('endDate', $dateTill)
            ->groupBy(
                sprintf('%s.id', 'u'),
                sprintf('%s.id', 'p'),
                sprintf('%s.id', 'pr'),
            );

        if (null !== $productId && '' !== $productId) {
            $qb
                ->andWhere(sprintf('%s.id = :productId', 'p'))
                ->setParameter('productId', $productId);
        }

        if (null !== $userId && '' !== $userId) {
            $qb
                ->andWhere(sprintf('%s.id = :userId', 'u'))
                ->setParameter('userId', $userId);
        }

        return $qb->getQuery()->getResult();
    }
}
