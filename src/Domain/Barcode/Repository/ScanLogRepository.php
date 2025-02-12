<?php
/**
 * Created by PhpStorm.
 * Date: 13.03.2024
 * Time: 21:24
 */

namespace App\Domain\Barcode\Repository;

use App\Domain\Barcode\Barcode;
use App\Domain\Barcode\ScanLog;
use App\Domain\Location\Location;
use App\Domain\Product\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ScanLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScanLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScanLog[]    findAll()
 * @method ScanLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScanLogRepository extends ServiceEntityRepository
{
    private const string ALIAS = 'sl';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScanLog::class);
    }

    public function findByInterval(
        \DateTimeImmutable $dateFrom,
        \DateTimeImmutable $dateTill,
        null|string $locationId,
        null|string $productId,
    ): array {
        $qb = $this->createQueryBuilder(self::ALIAS);

        $qb
            ->select(
                sprintf('%s.id as productId', 'p'),
                sprintf('%s.title as productTitle', 'p'),
                sprintf('%s.id as locationId', 'l'),
                sprintf('%s.title as locationTitle', 'l'),
                sprintf('SUM(IF(%s.status = 1, 1, 0)) AS countSuccess', self::ALIAS),
                sprintf('SUM(IF(%s.status = 0, 1, 0)) AS countDecline', self::ALIAS),
            )
            ->leftJoin(Barcode::class, 'bc', Join::WITH, sprintf('%s.barcode = bc.id', self::ALIAS))
            ->leftJoin(Product::class, 'p', Join::WITH, 'bc.product = p.id')
            ->leftJoin(Location::class, 'l', Join::WITH, sprintf('%s.location = l.id', self::ALIAS))
            ->where($qb->expr()->between(sprintf('%s.createdAt', self::ALIAS), ':startDate', ':endDate'))
            ->setParameter('startDate', $dateFrom)
            ->setParameter('endDate', $dateTill)
            ->groupBy(
                sprintf('%s.id', 'l'),
                sprintf('%s.id', 'p'),
            );

        if (null !== $locationId && '' !== $locationId) {
            $qb
                ->andWhere(sprintf('%s.id = :locationId', 'l'))
                ->setParameter('locationId', $locationId);
        }

        if (null !== $productId && '' !== $productId) {
            $qb
                ->andWhere(sprintf('%s.id = :productId', 'p'))
                ->setParameter('productId', $productId);
        }

        return $qb->getQuery()->getResult();
    }

    public function findByIntervalAggregatedByHours(
        \DateTimeImmutable $dateFrom,
        \DateTimeImmutable $dateTill,
        string $locationId,
        null|string $productId,
    ): array {
        $qb = $this->createQueryBuilder(self::ALIAS);

        $qb
            ->select(
                sprintf('CONCAT(DATE(%1$s.createdAt), \' \', HOUR(%1$s.createdAt)) AS title', self::ALIAS),
                'COUNT(1) AS countSuccess',
            )
            ->leftJoin(Barcode::class, 'bc', Join::WITH, sprintf('%s.barcode = bc.id', self::ALIAS))
            ->leftJoin(Product::class, 'p', Join::WITH, 'bc.product = p.id')
            ->leftJoin(Location::class, 'l', Join::WITH, sprintf('%s.location = l.id', self::ALIAS))
            ->where($qb->expr()->between(sprintf('%s.createdAt', self::ALIAS), ':startDate', ':endDate'))
            ->andWhere(sprintf('%s.status = :status', self::ALIAS))
            ->andWhere(sprintf('%s.id = :locationId', 'l'))
            ->setParameter('locationId', $locationId)
            ->setParameter('startDate', $dateFrom)
            ->setParameter('endDate', $dateTill)
            ->setParameter('status', true)
            ->groupBy('title');

        if (null !== $productId && '' !== $productId) {
            $qb
                ->andWhere(sprintf('%s.id = :productId', 'p'))
                ->setParameter('productId', $productId);
        }

        return $qb->getQuery()->getResult();
    }
}
