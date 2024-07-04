<?php

namespace App\Repository;

use App\Entity\AppLog;
use App\Entity\RefPays;
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
//        $stmt = $this->getEntityManager()->getConnection()->prepare("CREATE UNIQUE INDEX ref_maj_pkey
//            ON public.ref_maj USING btree (uuid);");
//        $stmt->executeQuery([]);
//        $stmt = $this->getEntityManager()->getConnection()->prepare("ALTER TABLE app_log
//            ADD CONSTRAINT app_log_pkey PRIMARY KEY (uuid);");
//        $stmt->executeQuery([]);
    }


    public function dataRefPays(AppLog $newAppLog, RefPays $refPays) {
        $newAppLog->setData("{
            \"uuid\" : \"" . $refPays->getUuid() . "\"," .
            "\"id_pays_sir\" : \"" . $refPays->getIdPaysSir() . "\"," .
            "\"libelle_pays_min\" : \"" . $refPays->getLibellePaysMin() . "\"," .
            "\"libelle_pays_maj\" : \"" . $refPays->getLibellePaysMaj() . "\"," .
            "\"code_iso_3\" : \"" . $refPays->getCodeIso3() . "\"," .
            "\"nationalite\" : \"" . $refPays->getNationalite() . "\"," .
            "\"personnel_creation\" : \"" . $refPays->getPersonnelCreation() . "\"," .
            "\"personnel_modification\" : \"" . $refPays->getPersonnelModification() . "\"," .
            "\"personnel_archivage\" : \"" . $refPays->getPersonnelArchivage() . "\"," .
            "\"date_heure_creation\" : \"" . ($refPays->getDateHeureCreation() == null ? null :
                $refPays->getDateHeureCreation()->format('Y-m-d H:i:s p')) . "\"," .
            "\"date_heure_modification\" : \"" . ($refPays->getDateHeureModification() == null ? null :
                $refPays->getDateHeureModification()->format('Y-m-d H:i:s p')) . "\"," .
            "\"date_heure_archivage\" : \"" . ($refPays->getDateHeureArchivage() == null ? null :
                $refPays->getDateHeureArchivage()->format('Y-m-d H:i:s p')) . "\"," .
            "\"archivage\" : \"" . $refPays->isArchivage() . "\",
        }\"");
    }

    public function fillingTheLogTableRefPays(Uuid $uidRef, AppLog $newAppLog, string $time, string $typeAction,
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

//    public function fillingTheLogTableRefRegion(RefRegion $refRegion, string $time) {
//
//
//    }

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
