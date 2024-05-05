<?php
/**
 * Created by PhpStorm.
 * Date: 31.01.2024
 * Time: 23:08
 */

namespace App\Domain\Product\Repository;

use App\Domain\Product\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private const ALIAS = 'p';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param string[] $locationIdList
     * @return Product[]
     */
    public function findByLocationList(array $locationIdList): array
    {
        return
            $this->createQueryBuilder(self::ALIAS)
                ->select(sprintf('DISTINCT %s', self::ALIAS))
                ->andWhere(':locationList MEMBER OF p.locationList')
                ->setParameter('locationList', $locationIdList)
                ->getQuery()
                ->getResult();
    }
}
