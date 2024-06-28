<?php

namespace App\Service\Sir\Repository;

use App\Service\Sir\Entity\SirRegion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SirRegion>
 *
 * @method SirRegion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SirRegion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SirRegion[]    findAll()
 * @method SirRegion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SirRegionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SirRegion::class);
    }

    //    /**
    //     * @return SirRegion[] Returns an array of SirRegion objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SirRegion
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
