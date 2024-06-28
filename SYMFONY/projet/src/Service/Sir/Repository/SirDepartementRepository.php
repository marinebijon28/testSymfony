<?php

namespace App\Service\Sir\Repository;

use App\Service\Sir\Entity\SirDepartement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SirDepartement>
 *
 * @method SirDepartement|null find($id, $lockMode = null, $lockVersion = null)
 * @method SirDepartement|null findOneBy(array $criteria, array $orderBy = null)
 * @method SirDepartement[]    findAll()
 * @method SirDepartement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SirDepartementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SirDepartement::class);
    }

    //    /**
    //     * @return SirDepartement[] Returns an array of SirDepartement objects
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

    //    public function findOneBySomeField($value): ?SirDepartement
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
