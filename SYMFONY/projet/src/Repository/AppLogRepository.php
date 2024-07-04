<?php

namespace App\Repository;

use App\Entity\AppLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppLog>
 */
class AppLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppLog::class);
    }

    /** ifExistTable
     *
     * checks if the table is created if not it created it
     *
     * @return void
     * @throws Exception
     */
    public function ifExistTable()
    {
        $entityManager = $this->getEntityManager();

        $stmt = $this->getEntityManager()->getConnection()->prepare("
            CREATE TABLE IF NOT EXISTS app_log (
                uuid UUID PRIMARY KEY NOT NULL,
                date_heure_action TIMESTAMP(0) WITH TIME ZONE NOT NULL,
                microseconde INTEGER NOT NULL,
                id_anonyme TEXT, 
                nigend TEXT NOT NULL,
                personnel TEXT NOT NULL,
                type_action TEXT NOT NULL,
                adresse TEXT,
                clef TEXT,
                nom_table TEXT,
                data TEXT,
                
            )
        ");

    }

    //    /**
    //     * @return AppLog[] Returns an array of AppLog objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AppLog
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
