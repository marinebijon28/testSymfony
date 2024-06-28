<?php

namespace App\Service\Sir\Repository;

use App\Service\Sir\Entity\SirCommune;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SirCommune>
 *
 * @method SirCommune|null find($id, $lockMode = null, $lockVersion = null)
 * @method SirCommune|null findOneBy(array $criteria, array $orderBy = null)
 * @method SirCommune[]    findAll()
 * @method SirCommune[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SirCommuneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SirCommune::class);
    }

    //    /**
    //     * @return SirCommune[] Returns an array of SirCommune objects
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

    //    public function findOneBySomeField($value): ?SirCommune
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
