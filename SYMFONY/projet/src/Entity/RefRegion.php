<?php

namespace App\Entity;

use App\Repository\RefRegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: 'ref_region')]
#[ORM\Entity(repositoryClass: RefRegionRepository::class)]
#[ORM\Index(name: "pk__ref_region", columns: ["uuid"])]
#[ORM\Index(name: "fk__ref_region__ref_pays", columns: ["ref_pays"])]
#[ORM\Index(name: 'idx__ref_region__id_sir_archivage', columns: ["id_region_sir", "archivage"])]
#[ORM\Index(name: 'idx__ref_region__id_sir_libelle_min_maj_ajout_manuel', columns: ["id_region_sir", "libelle_region_min",
    "libelle_region_maj", "ajout_manuel"])]
#[ORM\Index(name: 'idx__ref_region__ajout_manuel_archivage', columns: ["ajout_manuel", "archivage"])]
class RefRegion
{
    #[ORM\Id]
    #[ORM\Column(name: 'uuid', type: 'uuid', nullable: false)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $uuid = null;

    #[ORM\Column(name: 'id_region_sir', type: Types::TEXT, nullable: true)]
    private ?string $idRegionSir = null;

    #[ORM\Column(name: 'libelle_region_min', type: Types::TEXT, nullable: true)]
    private ?string $libelleRegionMin = null;

    #[ORM\Column(name: 'libelle_region_maj', type: Types::TEXT, nullable: true)]
    private ?string $libelleRegionMaj = null;

    #[ORM\Column(name: 'ajout_manuel', type: Types::BOOLEAN, nullable: false)]
    private bool $ajoutManuel = false;

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

    #[ORM\ManyToOne(inversedBy: 'refRegions')]
    #[ORM\JoinColumn(name: 'ref_pays', referencedColumnName: 'uuid', nullable: false)]
    private ?RefPays $refPays = null;

    #[ORM\OneToMany(targetEntity: RefDepartement::class, mappedBy: 'refRegion')]
    private Collection $refDepartements;

    #[ORM\OneToMany(targetEntity: RefCommune::class, mappedBy: 'refRegion')]
    private Collection $refCommunes;

    public function __construct()
    {
        $this->refDepartements = new ArrayCollection();
        $this->refCommunes = new ArrayCollection();
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function getIdRegionSir(): ?string
    {
        return $this->idRegionSir;
    }

    public function setIdRegionSir(?string $idRegionSir): static
    {
        $this->idRegionSir = $idRegionSir;

        return $this;
    }

    public function getLibelleRegionMin(): ?string
    {
        return $this->libelleRegionMin;
    }

    public function setLibelleRegionMin(?string $libelleRegionMin): static
    {
        $this->libelleRegionMin = $libelleRegionMin;

        return $this;
    }

    public function getLibelleRegionMaj(): ?string
    {
        return $this->libelleRegionMaj;
    }

    public function setLibelleRegionMaj(?string $libelleRegionMaj): static
    {
        $this->libelleRegionMaj = $libelleRegionMaj;

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

    /**
     * @return Collection<int, RefDepartement>
     */
    public function getRefDepartements(): Collection
    {
        return $this->refDepartements;
    }

    public function addRefDepartement(RefDepartement $refDepartement): static
    {
        if (!$this->refDepartements->contains($refDepartement)) {
            $this->refDepartements->add($refDepartement);
            $refDepartement->setRefRegion($this);
        }

        return $this;
    }

    public function removeRefDepartement(RefDepartement $refDepartement): static
    {
        if ($this->refDepartements->removeElement($refDepartement)) {
            // set the owning side to null (unless already changed)
            if ($refDepartement->getRefRegion() === $this) {
                $refDepartement->setRefRegion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RefCommune>
     */
    public function getRefCommunes(): Collection
    {
        return $this->refCommunes;
    }

    public function addRefCommune(RefCommune $refCommune): static
    {
        if (!$this->refCommunes->contains($refCommune)) {
            $this->refCommunes->add($refCommune);
            $refCommune->setRefRegion($this);
        }

        return $this;
    }

    public function removeRefCommune(RefCommune $refCommune): static
    {
        if ($this->refCommunes->removeElement($refCommune)) {
            // set the owning side to null (unless already changed)
            if ($refCommune->getRefRegion() === $this) {
                $refCommune->setRefRegion(null);
            }
        }

        return $this;
    }

}
