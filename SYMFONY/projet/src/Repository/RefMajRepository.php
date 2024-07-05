<?php

namespace App\Repository;

use App\Entity\RefMaj;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

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
                nb_enregistrement_archivage TEXT NOT NULL
            )");
        $stmt->executeQuery([]);
    }

    public function insertValue(object $toSummarize) {
        $newRefMaj = new RefMaj();
        $newRefMaj->setUuid(Uuid::v7());
        $newRefMaj->setDateHeureDebut($toSummarize->dateStartTime);
        $newRefMaj->setDateHeureFin($toSummarize->dateStartTime);
        $newRefMaj->setDuree($toSummarize->duration);
        $newRefMaj->setNomTable("ref_" . $toSummarize->tableRef);
        $newRefMaj->setNbEnregistrementTotal($toSummarize->totalRecordNumber);
        $newRefMaj->setNbEnregistrementAjout($toSummarize->numberOfAdditions);
        $newRefMaj->setNbEnregistrementModification($toSummarize->numberOfChanges);
        $newRefMaj->setNbEnregistrementArchivage($toSummarize->numberOfArchives);
        $this->getEntityManager()->persist($newRefMaj);
        $this->getEntityManager()->flush();
    }
}
