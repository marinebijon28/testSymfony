<?php

namespace App\Repository;

use App\Entity\RefCommune;
use App\Entity\RefDepartement;
use App\Entity\RefPays;
use App\Entity\RefRegion;
use App\Service\Sir\Entity\SirCommune;
use App\Service\Sir\Entity\SirDepartement;
use DateTime;
use DateTimeZone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<RefCommune>
 */
class RefCommuneRepository extends ServiceEntityRepository
{
    private ObjectManager $_objectManagerRef;
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, RefCommune::class);
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
            CREATE TABLE IF NOT EXISTS ref_commune (
                uuid UUID PRIMARY KEY NOT NULL,
                ref_pays uuid NOT NULL,
                ref_region uuid NOT NULL,
                ref_departement uuid,
                id_commune_sir TEXT,
                ajout_manuel BOOLEAN NOT NULL,
                libelle_commune_min TEXT,
                libelle_commune_maj TEXT,
                codes_postaux TEXT,
                epsg4326_lat TEXT,
                epsg4326_long TEXT,
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
    }

    /** existsData
     *
     * if it does not find a result. It inserts into the table
     *
     * @param SirCommune $sir
     * @param RefPays $refPays
     * @param RefRegion $refRegion
     * @param RefDepartement $refDepartement
     * @return RefCommune
     * @throws \Exception
     */
    public function existsData(SirCommune $sir, RefPays $refPays, RefRegion $refRegion, RefDepartement $refDepartement):
    int
    {
        $res = $this->findOneBy([
            "idCommuneSir" => $sir->getIdCommune(),
            "libelleCommuneMin" => $sir->getLibelleCommuneMin(),
            "libelleCommuneMaj" => $sir->getLibelleCommuneMaj(),
            "codesPostaux" => $sir->getCodesPostaux(),
            "epsg4326Lat" => $sir->getEpsg4326Lat(),
            "epsg4326Long" => $sir->getEpsg4326Long(),
        ]);
        if ($res == null)
        {
            $newCommune = new RefCommune();
            $newCommune->setUuid(Uuid::v7());
            $newCommune->setRefPays($refPays);
            $newCommune->setRefRegion($refRegion);
            $newCommune->setRefDepartement($refDepartement);
            $newCommune->setIdCommuneSir($sir->getIdCommune());
            $newCommune->setAjoutManuel(false);
            $newCommune->setLibelleCommuneMin($sir->getLibelleCommuneMin());
            $newCommune->setLibelleCommuneMaj($sir->getLibelleCommuneMaj());
            $newCommune->setCodesPostaux($sir->getCodesPostaux());
            $newCommune->setEpsg4326Lat($sir->getEpsg4326Lat());
            $newCommune->setEpsg4326Long($sir->getEpsg4326Long());
            $date = new DateTime("now", new DateTimeZone('Europe/Dublin'));
            $newCommune->setDateHeureCreation($date);
            $newCommune->setPersonnelCreation("Administrateur");
            $newCommune->setDateHeureModification(NULL);
            $newCommune->setPersonnelModification(NULL);
            $newCommune->setDateHeureArchivage(NULL);
            $newCommune->setPersonnelArchivage(NULL);
            $newCommune->setArchivage(FALSE);
            $this->_objectManagerRef->persist($newCommune);
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
            FROM ref_commune
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
                FROM ref_commune
                WHERE archivage = true'
        );
        return (int)$stmt->executeStatement();
    }
}
