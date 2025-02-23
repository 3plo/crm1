<?php
/**
 * Created by PhpStorm.
 * Date: 10.02.2024
 * Time: 0:21
 */

namespace App\Domain\Location\Repository;

use App\Domain\Location\RegularScheduler;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RegularScheduler|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegularScheduler|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegularScheduler[]    findAll()
 * @method RegularScheduler[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegularSchedulerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegularScheduler::class);
    }
}
