<?php

namespace App\Repository;

use App\Entity\RefDepartement;
use App\Entity\RefPays;
use App\Entity\RefRegion;
use App\Service\Sir\Entity\SirDepartement;
use App\Service\Sir\Entity\SirPays;
use DateTime;
use DateTimeZone;
use App\Service\Sir\Entity\SirRegion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Driver\IBMDB2\Exception\PrepareFailed;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<RefRegion>
 */
class RefRegionRepository extends ServiceEntityRepository
{
    private ObjectManager $_objectManagerRef;
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, RefRegion::class);
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
            CREATE TABLE IF NOT EXISTS ref_region (
                uuid UUID PRIMARY KEY NOT NULL,
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
    }

    /** existsData
     *
     * if it does not find a result. It inserts into the table
     *
     * @param SirPays $sir
     * @return int return 1 if result else return 0
     * @throws \Exception
     */
    public function existsData(SirRegion $sir, RefPays $refPays): int
    {
        $res = $this->findOneBy([
            "idRegionSir" => $sir->getIdRegion(),
            "libelleRegionMin" => $sir->getLibelleRegionMin(),
            "libelleRegionMaj" => $sir->getLibelleRegionMaj()
        ]);
        if ($res == null) {
            $newRegion = new RefRegion();
            $newRegion->setUuid(Uuid::v7());
            $newRegion->setRefPays($refPays);
            $newRegion->setIdRegionSir($sir->getIdRegion());
            $newRegion->setLibelleRegionMin($sir->getLibelleRegionMin());
            $newRegion->setLibelleRegionMaj($sir->getLibelleRegionMaj());
            $newRegion->setAjoutManuel(FALSE);
            $date = new DateTime("now", new DateTimeZone('Europe/Dublin'));
            $newRegion->setDateHeureCreation($date);
            $newRegion->setPersonnelCreation("Administrateur");
            $newRegion->setDateHeureModification(NULL);
            $newRegion->setPersonnelModification(NULL);
            $newRegion->setDateHeureArchivage(NULL);
            $newRegion->setPersonnelArchivage(NULL);
            $newRegion->setArchivage(FALSE);
            $this->_objectManagerRef->persist($newRegion);
            $this->_objectManagerRef->flush();
            return 1;
        }
        return 0;
    }

    /** findByNbModifications
     *
     * return number row of personnel_modification is not null
     *
     * @return int return number row of personnel_modification is not null
     * @throws Exception
     */
    public function findByNbModifications(): int
    {
        $stmt = $this->getEntityManager()->getConnection()->prepare(
            'SELECT uuid
            FROM ref_region
            WHERE personnel_modification != \'NULL\''
        );
        return (int)$stmt->executeStatement();
    }

    /** findByNbOfArchives
     *
     * return number row of archivage is true
     *
     * @return int return number row of archivage is true
     * @throws Exception
     */
    public function findByNbOfArchives(): int
    {
        $stmt = $this->getEntityManager()->getConnection()->prepare(
            'SELECT uuid
                FROM ref_region
                WHERE archivage = true'
        );
        return (int)$stmt->executeStatement();
    }
}
