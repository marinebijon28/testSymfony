<?php

namespace App\Entity;

use App\Repository\RefCommuneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: 'ref_commune')]
#[ORM\Entity(repositoryClass: RefCommuneRepository::class)]
#[ORM\Index(name: "pk__ref_commune", columns: ["uuid"])]
#[ORM\Index(name: "fk__ref_commune__ref_pays", columns: ["ref_pays"])]
#[ORM\Index(name: "fk__ref_commune__ref_departement", columns: ["ref_departement"])]
#[ORM\Index(name: "fk__ref_commune__ref_region", columns: ["ref_region"])]
#[ORM\Index(name: 'idx__ref_commune__ref_reg_dep_id_sir_libelle_min_maj_archivage', columns: ["ref_pays", "ref_region", "ref_departement",
    "libelle_commune_min", "libelle_commune_maj", "id_commune_sir", "archivage"])]
#[ORM\Index(name: 'idx__ref_commune__ajout_manuel_archivage', columns: ["ajout_manuel", "archivage"])]
class RefCommune
{
    #[ORM\Id]
    #[ORM\Column(name: 'uuid', type: 'uuid', nullable: false)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $uuid = null;

    #[ORM\Column(name: 'id_commune_sir', type: Types::TEXT, nullable: true)]
    private ?string $idCommuneSir = null;

    #[ORM\Column(name: 'ajout_manuel', type: Types::BOOLEAN, nullable: false)]
    private bool $ajoutManuel = false;

    #[ORM\Column(name: 'libelle_commune_min', type: Types::TEXT, nullable: true)]
    private ?string $libelleCommuneMin = null;

    #[ORM\Column(name: 'libelle_commune_maj', type: Types::TEXT, nullable: true)]
    private ?string $libelleCommuneMaj = null;

    #[ORM\Column(name: 'codes_postaux', type: Types::TEXT, nullable: true)]
    private ?string $codesPostaux = null;

    #[ORM\Column(name: 'epsg4326_lat', type: Types::TEXT, nullable: true)]
    private ?string $epsg4326Lat = null;

    #[ORM\Column(name: 'epsg4326_long', type: Types::TEXT, nullable: true)]
    private ?string $epsg4326Long = null;

    #[ORM\Column(name: 'date_heure_creation', type: Types::DATETIMETZ_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $dateHeureCreation = null;

    #[ORM\Column(name: 'personnel_creation', type: Types::TEXT, nullable: false)]
    private ?string $personnelCreation = null;

    #[ORM\Column(name: 'date_heure_modification', type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateHeureModification = null;

    #[ORM\Column(name: 'personnel_modification', type: Types::TEXT, nullable: true)]
    private ?string $personnelModification = null;

    #[ORM\Column(name: 'date_heure_archivage', type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateHeureArchivage = null;

    #[ORM\Column(name: 'personnel_archivage', type: Types::TEXT, nullable: true)]
    private ?string $personnelArchivage = null;

    #[ORM\Column(name: 'archivage', type: Types::BOOLEAN, nullable: false)]
    private bool $archivage = false;

    #[ORM\ManyToOne(inversedBy: 'refCommunes')]
    #[ORM\JoinColumn(name: 'ref_pays', referencedColumnName: 'uuid', nullable: false)]
    private ?RefPays $refPays = null;

    #[ORM\ManyToOne(inversedBy: 'refCommunes')]
    #[ORM\JoinColumn(name: 'ref_region', referencedColumnName: 'uuid', nullable: false)]
    private ?RefRegion $refRegion = null;

    #[ORM\ManyToOne(inversedBy: 'refCommunes')]
    #[ORM\JoinColumn(name: 'ref_departement', referencedColumnName: 'uuid', nullable: true)]
    private ?RefDepartement $refDepartement = null;

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function getIdCommuneSir(): ?string
    {
        return $this->idCommuneSir;
    }

    public function setIdCommuneSir(?string $idCommuneSir): static
    {
        $this->idCommuneSir = $idCommuneSir;

        return $this;
    }

    public function isAjoutManuel(): ?bool
    {
        return $this->ajoutManuel;
    }

    public function setAjoutManuel(bool $ajoutManuel): static
    {
        $this->ajoutManuel = $ajoutManuel;

        return $this;
    }

    public function getLibelleCommuneMin(): ?string
    {
        return $this->libelleCommuneMin;
    }

    public function setLibelleCommuneMin(?string $libelleCommuneMin): static
    {
        $this->libelleCommuneMin = $libelleCommuneMin;

        return $this;
    }

    public function getLibelleCommuneMaj(): ?string
    {
        return $this->libelleCommuneMaj;
    }

    public function setLibelleCommuneMaj(?string $libelleCommuneMaj): static
    {
        $this->libelleCommuneMaj = $libelleCommuneMaj;

        return $this;
    }

    public function getCodesPostaux(): ?string
    {
        return $this->codesPostaux;
    }

    public function setCodesPostaux(?string $codesPostaux): static
    {
        $this->codesPostaux = $codesPostaux;

        return $this;
    }

    public function getEpsg4326Lat(): ?string
    {
        return $this->epsg4326Lat;
    }

    public function setEpsg4326Lat(?string $epsg4326Lat): static
    {
        $this->epsg4326Lat = $epsg4326Lat;

        return $this;
    }

    public function getEpsg4326Long(): ?string
    {
        return $this->epsg4326Long;
    }

    public function setEpsg4326Long(?string $epsg4326Long): static
    {
        $this->epsg4326Long = $epsg4326Long;

        return $this;
    }

    public function getDateHeureCreation(): ?\DateTimeInterface
    {
        return $this->dateHeureCreation;
    }

    public function setDateHeureCreation(\DateTimeInterface $dateHeureCreation): static
    {
        $this->dateHeureCreation = $dateHeureCreation;

        return $this;
    }

    public function getPersonnelCreation(): ?string
    {
        return $this->personnelCreation;
    }

    public function setPersonnelCreation(string $personnelCreation): static
    {
        $this->personnelCreation = $personnelCreation;

        return $this;
    }

    public function getDateHeureModification(): ?\DateTimeInterface
    {
        return $this->dateHeureModification;
    }

    public function setDateHeureModification(?\DateTimeInterface $dateHeureModification): static
    {
        $this->dateHeureModification = $dateHeureModification;

        return $this;
    }

    public function getPersonnelModification(): ?string
    {
        return $this->personnelModification;
    }

    public function setPersonnelModification(?string $personnelModification): static
    {
        $this->personnelModification = $personnelModification;

        return $this;
    }

    public function getDateHeureArchivage(): ?\DateTimeInterface
    {
        return $this->dateHeureArchivage;
    }

    public function setDateHeureArchivage(?\DateTimeInterface $dateHeureArchivage): static
    {
        $this->dateHeureArchivage = $dateHeureArchivage;

        return $this;
    }

    public function getPersonnelArchivage(): ?string
    {
        return $this->personnelArchivage;
    }

    public function setPersonnelArchivage(?string $personnelArchivage): static
    {
        $this->personnelArchivage = $personnelArchivage;

        return $this;
    }

    public function isArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(bool $archivage): static
    {
        $this->archivage = $archivage;

        return $this;
    }

    public function getRefPays(): ?RefPays
    {
        return $this->refPays;
    }

    public function setRefPays(?RefPays $refPays): static
    {
        $this->refPays = $refPays;

        return $this;
    }

    public function getRefRegion(): ?RefRegion
    {
        return $this->refRegion;
    }

    public function setRefRegion(?RefRegion $refRegion): static
    {
        $this->refRegion = $refRegion;

        return $this;
    }

    public function getRefDepartement(): ?RefDepartement
    {
        return $this->refDepartement;
    }

    public function setRefDepartement(?RefDepartement $refDepartement): static
    {
        $this->refDepartement = $refDepartement;

        return $this;
    }

}

