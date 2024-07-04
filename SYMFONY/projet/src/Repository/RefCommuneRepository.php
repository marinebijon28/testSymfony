<?php

namespace App\Repository;

use App\Entity\RefCommune;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RefCommune>
 */
class RefCommuneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefCommune::class);
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
            CREATE TABLE IF NOT EXISTS ref_commune (
                uuid uuid PRIMARY KEY NOT NULL,
                ref_pays uuid NOT NULL,
                ref_region uuid NOT NULL,
                ref_departement uuid,
                id_commune_sir TEXT,
                ajout_manuel BOOLEAN NOT NULL,
                libelle_commune_min TEXT,
                libelle_commune_maj TEXT,
                code_postaux TEXT,
                epsg_4326_lat TEXT,
                epsg_4326_long TEXT,
                date_heure_creation TIMESTAMP(0) WITH TIME ZONE NOT NULL,
                personnel_creation TEXT NOT NULL,
                date_heure_modification TIMESTAMP(0) WITH TIME ZONE,
                personnel_modification TEXT,
                date_heure_archivage TEXT,
                personnel_archivage TEXT,
                archivage BOOLEAN NOT NULL,
                CONSTRAINT fk_26f6823e23ec7d29 FOREIGN KEY (ref_pays) REFERENCES ref_pays(uuid)
            );");
        $stmt->executeStatement();

        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS 
            fk__ref_commune__ref_departement ON public.ref_commune USING btree (ref_departement);");
        $stmt->executeQuery([]);
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS 
            fk__ref_commune__ref_pays ON public.ref_commune USING btree (ref_pays);");
        $stmt->executeQuery([]);
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS 
            fk__ref_commune__ref_region ON public.ref_commune USING btree (ref_region);");
        $stmt->executeQuery([]);
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS
            idx__ref_commune__ajout_manuel_archivage ON public.ref_commune USING btree (ajout_manuel, archivage);");
        $stmt->executeQuery([]);
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS
            idx__ref_commune__ref_reg_dep_id_sir_libelle_min_maj_archivage ON public.ref_commune 
            USING btree (ref_pays, ref_region, ref_departement, libelle_commune_min, libelle_commune_maj, 
            id_commune_sir, archivage);");
        $stmt->executeQuery([]);
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS 
            pk__ref_commune ON public.ref_commune USING btree (uuid);");
        $stmt->executeQuery([]);
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE UNIQUE INDEX IF NOT EXISTS 
            ref_commune_pkey ON public.ref_commune USING btree (uuid);");
        $stmt->executeQuery([]);
//        $stmt = $this->getEntityManager()->getConnection()->prepare("ALTER TABLE public.ref_commune ADD CONSTRAINT
//            ref_commune_pkey PRIMARY KEY (uuid);");
//        $stmt->executeQuery([]);
//        $stmt = $this->getEntityManager()->getConnection()->prepare("ALTER TABLE public.ref_commune ADD CONSTRAINT
//            fk_26f6823e23ec7d29 FOREIGN KEY (ref_pays) REFERENCES ref_pays(uuid);");
//        $stmt->executeQuery([]);
//        $stmt = $this->getEntityManager()->getConnection()->prepare("ALTER TABLE public.ref_commune ADD CONSTRAINT
//            fk_26f6823e3b7fa3ef FOREIGN KEY (ref_departement) REFERENCES ref_departement(uuid);");
//        $stmt->executeQuery([]);
//        $stmt = $this->getEntityManager()->getConnection()->prepare("ALTER TABLE public.ref_commune ADD CONSTRAINT
//            fk_26f6823e3b7fa3ef FOREIGN KEY (ref_departement) REFERENCES ref_departement(uuid);");
//        $stmt->executeQuery([]);
    }

    //    /**
    //     * @return RefCommune[] Returns an array of RefCommune objects
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

    //    public function findOneBySomeField($value): ?RefCommune
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
