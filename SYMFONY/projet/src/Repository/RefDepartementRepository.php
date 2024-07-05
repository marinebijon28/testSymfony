<?php

namespace App\Repository;

use App\Entity\RefDepartement;
use App\Entity\RefRegion;
use App\Service\Sir\Entity\SirDepartement;
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

    /** existsData
     *
     * if it does not find a result. It inserts into the table
     *
     * @param SirDepartement $sir
     * @param RefRegion $refRegion
     * @return int
     * @throws \Exception
     */
    public function existsData(SirDepartement $sir, RefRegion $refRegion): int
    {
        $res = $this->findOneBy([
            "idDepartementSir" => $sir->getIdRegion(),
            "libelleDepartementMin" => $sir->getLibelleDepartementMin(),
            "libelleDepartementMaj" => $sir->getLibelleDepartementMaj()
        ]);
        if ($res == null)
        {
            $newDepartement = new RefDepartement();
            $newDepartement->setUuid(Uuid::v7());
            $newDepartement->setRefRegion($refRegion);
            $newDepartement->setIdDepartementSir($sir->getIdDepartement());
            $newDepartement->setNumero(NULL);
            $newDepartement->setLibelleDepartementMin($sir->getLibelleDepartementMin());
            $newDepartement->setLibelleDepartementMaj($sir->getLibelleDepartementMaj());
            $newDepartement->setDateHeureCreation(new DateTime("now",
                new DateTimeZone('Europe/Dublin')));
            $newDepartement->setPersonnelCreation("Administrateur");
            $newDepartement->setDateHeureModification(NULL);
            $newDepartement->setPersonnelModification(NULL);
            $newDepartement->setDateHeureArchivage(NULL);
            $newDepartement->setPersonnelArchivage(NULL);
            $newDepartement->setArchivage(FALSE);
            $this->_objectManagerRef->persist($newDepartement);
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
            FROM ref_departement
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
                FROM ref_departement
                WHERE archivage = true'
        );
        return (int)$stmt->executeStatement();
    }
}
