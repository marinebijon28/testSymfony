<?php

namespace App\Repository;

use App\Entity\AppLog;
use App\Entity\RefCommune;
use App\Entity\RefDepartement;
use App\Entity\RefPays;
use App\Entity\RefRegion;
use DateTime;
use DateTimeZone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

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
                data TEXT
            )");
        $stmt->executeQuery([]);
    }


    public function dataRefPays(AppLog $newAppLog, RefPays $refPays) {
        $newAppLog->setData("{
            \"uuid\" : \"" . $refPays->getUuid() . "\",
            \"id_pays_sir\" : \"" . $refPays->getIdPaysSir() . "\",
            \"libelle_pays_min\" : \"" . $refPays->getLibellePaysMin() . "\",
            \"libelle_pays_maj\" : \"" . $refPays->getLibellePaysMaj() . "\",
            \"code_iso_3\" : \"" . $refPays->getCodeIso3() . "\",
            \"nationalite\" : \"" . $refPays->getNationalite() . "\",
            \"personnel_creation\" : \"" . $refPays->getPersonnelCreation() . "\",
            \"personnel_modification\" : \"" . $refPays->getPersonnelModification() . "\",
            \"personnel_archivage\" : \"" . $refPays->getPersonnelArchivage() . "\",
            \"date_heure_creation\" : \"" . ($refPays->getDateHeureCreation() == null ? null :
                $refPays->getDateHeureCreation()->format('Y-m-d H:i:s p')) . "\",
            \"date_heure_modification\" : \"" . ($refPays->getDateHeureModification() == null ? null :
                $refPays->getDateHeureModification()->format('Y-m-d H:i:s p')) . "\",
            \"date_heure_archivage\" : \"" . ($refPays->getDateHeureArchivage() == null ? null :
                $refPays->getDateHeureArchivage()->format('Y-m-d H:i:s p')) . "\",
            \"archivage\" : \"" . $refPays->isArchivage() . "\",
        }");
    }
    public function dataRefRegion(AppLog $newAppLog, RefRegion $refRegion) {
        $newAppLog->setData("{
            \"uuid\" : \"" . $refRegion->getUuid() . "\",
            \"ref_rpays\" : \"" . $refRegion->getRefPays()->getUuid() . "\",
            \"id_region_sir\" : \"" . $refRegion->getIdRegionSir() . "\",
            \"libelle_region_min\" : \"" . $refRegion->getLibelleRegionMin() . "\",
            \"libelle_region_maj\" : \"" . $refRegion->getLibelleRegionMaj() . "\",
            \"ajout_manuel\" : \"" . $refRegion->isAjoutManuel() . "\",
            \"personnel_creation\" : \"" . $refRegion->getPersonnelCreation() . "\",
            \"personnel_modification\" : \"" . $refRegion->getPersonnelModification() . "\",
            \"personnel_archivage\" : \"" . $refRegion->getPersonnelArchivage() . "\",
            \"date_heure_creation\" : \"" . ($refRegion->getDateHeureCreation() == null ? null :
                $refRegion->getDateHeureCreation()->format('Y-m-d H:i:s p')) . "\",
            \"date_heure_modification\" : \"" . ($refRegion->getDateHeureModification() == null ? null :
                $refRegion->getDateHeureModification()->format('Y-m-d H:i:s p')) . "\",
            \"date_heure_archivage\" : \"" . ($refRegion->getDateHeureArchivage() == null ? null :
                $refRegion->getDateHeureArchivage()->format('Y-m-d H:i:s p')) . "\",
            \"archivage\" : \"" . $refRegion->isArchivage() . "\",
        }");
    }

    public function dataRefDepartement(AppLog $newAppLog, RefDepartement $refDepartement) {
        $newAppLog->setData("{
            \"uuid\" : \"" . $refDepartement->getUuid() . "\",
            \"ref_region\" : \"" . $refDepartement->getRefRegion()->getUuid() . "\",
            \"id_departement_sir\" : \"" . $refDepartement->getIdDepartementSir() . "\",
            \"libelle_departement_min\" : \"" . $refDepartement->getLibelleDepartementMin() . "\",
            \"libelle_departement_maj\" : \"" . $refDepartement->getLibelleDepartementMaj() . "\",
            \"personnel_creation\" : \"" . $refDepartement->getPersonnelCreation() . "\",
            \"personnel_modification\" : \"" . $refDepartement->getPersonnelModification() . "\",
            \"personnel_archivage\" : \"" . $refDepartement->getPersonnelArchivage() . "\",
            \"date_heure_creation\" : \"" . ($refDepartement->getDateHeureCreation() == null ? null :
                $refDepartement->getDateHeureCreation()->format('Y-m-d H:i:s p')) . "\",
            \"date_heure_modification\" : \"" . ($refDepartement->getDateHeureModification() == null ? null :
                $refDepartement->getDateHeureModification()->format('Y-m-d H:i:s p')) . "\",
            \"date_heure_archivage\" : \"" . ($refDepartement->getDateHeureArchivage() == null ? null :
                $refDepartement->getDateHeureArchivage()->format('Y-m-d H:i:s p')) . "\",
            \"archivage\" : \"" . $refDepartement->isArchivage() . "\",
        }");
    }

    public function dataRefCommune(AppLog $newAppLog, RefCommune $RefCommune) {
        $newAppLog->setData("{
            \"uuid\" : \"" . $RefCommune->getUuid() . "\",
            \"ref_pays\" : \"" . $RefCommune->getRefPays()->getUuid() . "\",
            \"ref_region\" : \"" . $RefCommune->getRefRegion()->getUuid() . "\",
            \"ref_departement\" : \"" . $RefCommune->getRefDepartement()->getUuid() . "\",
            \"id_commune_sir\" : \"" . $RefCommune->getIdCommuneSir() . "\",
            \"ajout_manuel\" : \"" . $RefCommune->isAjoutManuel() . "\",
            \"libelle_commune_min\" : \"" . $RefCommune->getLibelleCommuneMin() . "\",
            \"libelle_commune_maj\" : \"" . $RefCommune->getLibelleCommuneMaj() . "\",
            \"codes_postaux\" : \"" . $RefCommune->getCodesPostaux() . "\",
            \"epsg4326_lat\" : \"" . $RefCommune->getEpsg4326Lat() . "\",
            \"epsg4326_long\" : \"" . $RefCommune->getEpsg4326Long() . "\",
            \"personnel_creation\" : \"" . $RefCommune->getPersonnelCreation() . "\",
            \"personnel_modification\" : \"" . $RefCommune->getPersonnelModification() . "\",
            \"personnel_archivage\" : \"" . $RefCommune->getPersonnelArchivage() . "\",
            \"date_heure_creation\" : \"" . ($RefCommune->getDateHeureCreation() == null ? null :
                $RefCommune->getDateHeureCreation()->format('Y-m-d H:i:s p')) . "\",
            \"date_heure_modification\" : \"" . ($RefCommune->getDateHeureModification() == null ? null :
                $RefCommune->getDateHeureModification()->format('Y-m-d H:i:s p')) . "\",
            \"date_heure_archivage\" : \"" . ($RefCommune->getDateHeureArchivage() == null ? null :
                $RefCommune->getDateHeureArchivage()->format('Y-m-d H:i:s p')) . "\",
            \"archivage\" : \"" . $RefCommune->isArchivage() . "\",
        }");
    }

    public function fillingTheLogTableRef(Uuid $uidRef, AppLog $newAppLog, string $time, string $typeAction,
                                              string $nameTable) {
        $newAppLog->setUuid(Uuid::v7());
        $date = new DateTime("now", new DateTimeZone('Europe/Dublin'));
        $newAppLog->setDateHeureAction($date);
        $newAppLog->setMicroseconde((int)substr($time, -6));
        $newAppLog->setIdAnonyme(NULL);
        $newAppLog->setNigend("0");
        $newAppLog->setPersonnel("Administrateur");
        $newAppLog->setTypeAction($typeAction);
        $newAppLog->setAdresse(NULL);
        $newAppLog->setClef($uidRef);
        $newAppLog->setNomTable($nameTable);
        $this->getEntityManager()->persist($newAppLog);
        $this->getEntityManager()->flush();
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
