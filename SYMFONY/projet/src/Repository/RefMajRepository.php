<?php

namespace App\Repository;

use App\Entity\RefMaj;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RefMaj>
 */
class RefMajRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefMaj::class);
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
            CREATE TABLE IF NOT EXISTS ref_maj (
                uuid UUID PRIMARY KEY NOT NULL,
                date_heure_debut TIMESTAMP(0) WITH TIME ZONE NOT NULL,
                date_heure_fin TIMESTAMP(0) WITH TIME ZONE NOT NULL,
                duree TEXT NOT NULL,
                nom_table TEXT NOT NULL,
                nb_enregistrement_total TEXT NOT NULL,
                nb_enregistrement_ajout TEXT NOT NULL,
                nb_enregistrement_modification TEXT NOT NULL,
                nb_enregistrement_archivage NOT NULL,
            )");
    }

    //    /**
    //     * @return RefMaj[] Returns an array of RefMaj objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RefMaj
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
