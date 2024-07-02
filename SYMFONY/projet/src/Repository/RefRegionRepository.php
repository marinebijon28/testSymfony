<?php

namespace App\Repository;

use App\Entity\RefRegion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RefRegion>
 */
class RefRegionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefRegion::class);
    }

    /** ifExistTableRefRegion
     *
     * checks if the table is created if not it created it
     *
     * @return void
     * @throws Exception
     */
    public function ifExistTableRefRegion()
    {
        $entityManager = $this->getEntityManager();
        // fk ref_pays
        $stmt = $this->getEntityManager()->getConnection()->prepare("
            CREATE TABLE IF NOT EXISTS ref_region (
                uuid uuid PRIMARY KEY NOT NULL,
                ref_pays uuid NOT NULL, 
                id_region_sir TEXT,
                libelle_region_min TEXT,
                libelle_region_maj TEXT,
                ajout_manuel BOOLEAN NOT NULL,
                date_heure_creation TIMESTAMP(0) WITH TIME ZONE NOT NULL,
                personnel_creation TEXT NOT NULL,
                date_heure_modification TIMESTAMP(0) WITH TIME ZONE,
                personnel_modification TEXT,
                date_heure_archivage TEXT,
                personnel_archivage TEXT,
                archivage BOOLEAN NOT NULL,
                CONSTRAINT fk_7a7b998f23ec7d29 FOREIGN KEY (ref_pays) REFERENCES ref_pays(uuid)
/*                CONSTRAINT ref_region_pkey PRIMARY KEY (uuid) */
            );");
        $stmt->executeStatement();
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS 
            fk__ref_region__ref_pays ON public.ref_region USING btree (ref_pays);");
        $stmt->executeQuery([]);
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS 
            idx__ref_region__ajout_manuel_archivage ON public.ref_region USING btree (ajout_manuel, archivage);");
        $stmt->executeQuery([]);
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS 
            idx__ref_region__id_sir_libelle_min_maj_ajout_manuel ON public.ref_region 
            USING btree (id_region_sir, libelle_region_min, libelle_region_maj, ajout_manuel);");
        $stmt->executeQuery([]);
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS  pk__ref_region 
            ON public.ref_region USING btree (uuid);");
        $stmt->executeQuery([]);
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE UNIQUE INDEX IF NOT EXISTS
            ref_region_pkey ON public.ref_region USING btree (uuid);");
        $stmt->executeQuery([]);
//        $stmt = $this->getEntityManager()->getConnection()->prepare("ALTER TABLE public.ref_region ADD
//                CONSTRAINT fk_7a7b998f23ec7d29 FOREIGN KEY (ref_pays) REFERENCES ref_pays(uuid);");
//        $stmt->executeQuery([]);
//        $stmt = $this->getEntityManager()->getConnection()->prepare("ALTER TABLE public.ref_region ADD CONSTRAINT
//            ref_region_pkey PRIMARY KEY (uuid);");
//        $stmt->executeQuery([]);
    }

    //    /**
    //     * @return RefRegion[] Returns an array of RefRegion objects
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

    //    public function findOneBySomeField($value): ?RefRegion
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
