<?php
/**
 * Created by PhpStorm.
 * Date: 10.02.2024
 * Time: 0:21
 */

namespace App\Domain\Location\Repository;

use App\Domain\Location\VacationScheduler;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VacationScheduler|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacationScheduler|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacationScheduler[]    findAll()
 * @method VacationScheduler[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacationSchedulerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VacationScheduler::class);
    }
}
