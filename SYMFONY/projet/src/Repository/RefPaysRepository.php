<?php

namespace App\Repository;

use App\Entity\RefPays;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RefPays>
 */
class RefPaysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefPays::class);
    }

    /** ifExistTableRefPays
     *
     * checks if the table is created if not it created it
     *
     * @return void
     * @throws Exception
     */
    public function ifExistTableRefPays()
    {
        $entityManager = $this->getEntityManager();

        $stmt = $this->getEntityManager()->getConnection()->prepare("
            CREATE TABLE IF NOT EXISTS ref_pays (
                uuid uuid PRIMARY KEY NOT NULL,
                id_pays_sir TEXT,
                libelle_pays_min TEXT,
                libelle_pays_maj TEXT,
                code_iso_3 TEXT,
                nationalite TEXT,
                date_heure_creation TIMESTAMP(0) WITH TIME ZONE NOT NULL,
                personnel_creation TEXT NOT NULL,
                date_heure_modification TIMESTAMP(0) WITH TIME ZONE,
                personnel_modification TEXT,
                date_heure_archivage TIMESTAMP(0) WITH TIME ZONE,
                personnel_archivage TEXT,
                archivage BOOLEAN NOT NULL
            );");
            $stmt->executeStatement();
            $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE UNIQUE INDEX IF NOT EXISTS 
                ref_pays_pkey ON ref_pays USING btree (uuid);");
            $stmt->executeQuery([]);
            $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS pk_ref_pays
                ON ref_pays USING btree (uuid);");
            $stmt->executeQuery([]);
            $stmt = $this->getEntityManager()->getConnection()->prepare("create INDEX IF NOT EXISTS
                idx__ref_pays__id_sir_libelle_min_maj ON ref_pays USING btree (id_pays_sir, libelle_pays_min, 
                libelle_pays_maj);");
            $stmt->executeQuery([]);
            $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS
                idx__ref_pays__archivage ON ref_pays USING btree (archivage);");
            $stmt->executeQuery([]);
    }

    //    /**
    //     * @return RefPays[] Returns an array of RefPays objects
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

    //    public function findOneBySomeField($value): ?RefPays
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
