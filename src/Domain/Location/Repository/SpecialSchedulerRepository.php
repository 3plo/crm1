<?php
/**
 * Created by PhpStorm.
 * Date: 10.02.2024
 * Time: 0:21
 */

namespace App\Domain\Location\Repository;

use App\Domain\Location\SpecialScheduler;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpecialScheduler|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpecialScheduler|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpecialScheduler[]    findAll()
 * @method SpecialScheduler[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialSchedulerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpecialScheduler::class);
    }
}
