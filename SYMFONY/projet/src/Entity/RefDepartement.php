<?php

namespace App\Entity;

use App\Repository\RefDepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: 'ref_departement')]
#[ORM\Entity(repositoryClass: RefDepartementRepository::class)]
#[ORM\Index(name: "pk__ref_departement", columns: ["uuid"])]
#[ORM\Index(name: "fk__ref_departement__ref_region", columns: ["ref_region"])]
#[ORM\Index(name: 'idx__ref_departement__archivage', columns: ["archivage"])]
#[ORM\Index(name: 'idx__ref_departement__id_sir_archivage', columns: ["id_departement_sir", "archivage"])]
#[ORM\Index(name: 'idx__ref_departement__id_sir_libelle_min_maj', columns: ["id_departement_sir", "libelle_departement_min", "libelle_departement_maj"])]
class RefDepartement
{
    #[ORM\Id]
    #[ORM\Column(name: 'uuid', type: 'uuid', nullable: false)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $uuid = null;

    #[ORM\Column(name: 'id_departement_sir', type: Types::TEXT, nullable: true)]
    private ?string $idDepartementSir = null;

    #[ORM\Column(name: 'numero', type: Types::TEXT, nullable: true)]
    private ?string $numero = null;

    #[ORM\Column(name: 'libelle_departement_min', type: Types::TEXT, nullable: true)]
    private ?string $libelleDepartementMin = null;

    #[ORM\Column(name: 'libelle_departement_maj', type: Types::TEXT, nullable: true)]
    private ?string $libelleDepartementMaj = null;

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

    #[ORM\ManyToOne(inversedBy: 'refDepartements')]
    #[ORM\JoinColumn(name: 'ref_region', referencedColumnName: 'uuid', nullable: false)]
    private ?RefRegion $refRegion = null;

    #[ORM\OneToMany(targetEntity: RefCommune::class, mappedBy: 'refDepartement')]
    private Collection $refCommunes;

    public function __construct()
    {
        $this->refCommunes = new ArrayCollection();
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function getIdDepartementSir(): ?string
    {
        return $this->idDepartementSir;
    }

    public function setIdDepartementSir(?string $idDepartementSir): static
    {
        $this->idDepartementSir = $idDepartementSir;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getLibelleDepartementMin(): ?string
    {
        return $this->libelleDepartementMin;
    }

    public function setLibelleDepartementMin(?string $libelleDepartementMin): static
    {
        $this->libelleDepartementMin = $libelleDepartementMin;

        return $this;
    }

    public function getLibelleDepartementMaj(): ?string
    {
        return $this->libelleDepartementMaj;
    }

    public function setLibelleDepartementMaj(?string $libelleDepartementMaj): static
    {
        $this->libelleDepartementMaj = $libelleDepartementMaj;

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

    public function getDateHeureModification(): ?\DateTimeInterface
    {
        return $this->dateHeureModification;
    }

    public function setDateHeureModification(?\DateTimeInterface $dateHeureModification): static
    {
        $this->dateHeureModification = $dateHeureModification;

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

    public function isArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(bool $archivage): static
    {
        $this->archivage = $archivage;

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
            $refCommune->setRefDepartement($this);
        }

        return $this;
    }

    public function removeRefCommune(RefCommune $refCommune): static
    {
        if ($this->refCommunes->removeElement($refCommune)) {
            // set the owning side to null (unless already changed)
            if ($refCommune->getRefDepartement() === $this) {
                $refCommune->setRefDepartement(null);
            }
        }

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

    public function getPersonnelModification(): ?string
    {
        return $this->personnelModification;
    }

    public function setPersonnelModification(?string $personnelModification): static
    {
        $this->personnelModification = $personnelModification;

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

}
