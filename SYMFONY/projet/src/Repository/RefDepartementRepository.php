<?php

namespace App\Repository;

use App\Entity\RefDepartement;
use App\Entity\RefPays;
use App\Entity\RefRegion;
use App\Service\Sir\Entity\SirDepartement;
use App\Service\Sir\Entity\SirRegion;
use DateTime;
use DateTimeZone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<RefDepartement>
 */
class RefDepartementRepository extends ServiceEntityRepository
{
    private ObjectManager $_objectManagerRef;
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, RefDepartement::class);
        $this->_objectManagerRef = $managerRegistry->getManager('default');
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
        $stmt = $this->getEntityManager()->getConnection()->prepare("
            CREATE TABLE IF NOT EXISTS ref_departement (
                uuid UUID PRIMARY KEY NOT NULL,
                ref_region UUID NOT NULL,
                id_departement_sir TEXT,
                numero TEXT,
                libelle_departement_min TEXT,
                libelle_departement_maj TEXT,
                date_heure_creation TIMESTAMP(0) WITH TIME ZONE NOT NULL,
                personnel_creation TEXT NOT NULL,
                date_heure_modification TIMESTAMP(0) WITH TIME ZONE,
                personnel_modification TEXT,
                date_heure_archivage TIMESTAMP(0) WITH TIME ZONE,
                personnel_archivage TEXT,
                archivage BOOLEAN NOT NULL,
             /*   CONSTRAINT ref_departement_pkey PRIMARY KEY (uuid),*/
                CONSTRAINT fk_3b7fa3ef7a7b998f FOREIGN KEY (ref_region) REFERENCES ref_region(uuid)
            );");
        $stmt->executeStatement();
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS 
            fk__ref_departement__ref_region ON public.ref_departement USING btree (ref_region);");
        $stmt->executeQuery([]);
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS 
            idx__ref_departement__archivage ON public.ref_departement USING btree (archivage);");
        $stmt->executeQuery([]);
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS 
            idx__ref_departement__id_sir_archivage ON public.ref_departement 
            USING btree (id_departement_sir, archivage);");
        $stmt->executeQuery([]);
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS 
            idx__ref_departement__id_sir_libelle_min_maj ON public.ref_departement 
            USING btree (id_departement_sir, libelle_departement_min, libelle_departement_maj);");
        $stmt->executeQuery([]);
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE INDEX IF NOT EXISTS 
            pk__ref_departement ON public.ref_departement USING btree (uuid);");
        $stmt->executeQuery([]);
        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE UNIQUE INDEX IF NOT EXISTS 
            ref_departement_pkey ON public.ref_departement USING btree (uuid);");
        $stmt->executeQuery([]);
    }

    public function existsData(SirDepartement $sir, RefRegion $refRegion): void
    {
        if ($this->findOneBy([
                "idDepartementSir" => $sir->getIdRegion(),
                "libelleDepartementMin" => $sir->getLibelleDepartementMin(),
                "libelleDepartementMaj" => $sir->getLibelleDepartementMaj()
            ]) == null)
        {
            $newDepartement = new RefDepartement();
            $newDepartement->setUuid(Uuid::v7());
            $newDepartement->setRefRegion($refRegion);
            $newDepartement->setIdDepartementSir($sir->getIdDepartement());
            $newDepartement->setNumero(NULL);
            $newDepartement->setLibelleDepartementMin($sir->getLibelleDepartementMin());
            $newDepartement->setLibelleDepartementMaj($sir->getLibelleDepartementMaj());
            $date = new DateTime("now", new DateTimeZone('Europe/Dublin'));
            $newDepartement->setDateHeureCreation($date);
            $newDepartement->setPersonnelCreation("Administrateur");
            $newDepartement->setDateHeureModification(NULL);
            $newDepartement->setPersonnelModification(NULL);
            $newDepartement->setDateHeureArchivage(NULL);
            $newDepartement->setPersonnelArchivage(NULL);
            $newDepartement->setArchivage(FALSE);
            $this->_objectManagerRef->persist($newDepartement);
            $this->_objectManagerRef->flush();
        }
    }



    //    /**
    //     * @return RefDepartement[] Returns an array of RefDepartement objects
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

    //    public function findOneBySomeField($value): ?RefDepartement
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
