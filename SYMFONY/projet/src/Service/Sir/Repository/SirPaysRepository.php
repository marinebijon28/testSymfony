<?php

namespace App\Service\Sir\Repository;

use App\Service\Sir\Entity\SirPays;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SirPays>
 *
 * @method SirPays|null find($id, $lockMode = null, $lockVersion = null)
 * @method SirPays|null findOneBy(array $criteria, array $orderBy = null)
 * @method SirPays[]    findAll()
 * @method SirPays[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SirPaysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SirPays::class);
    }

    //    /**
    //     * @return SirPays[] Returns an array of SirPays objects
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

    //    public function findOneBySomeField($value): ?SirPays
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
