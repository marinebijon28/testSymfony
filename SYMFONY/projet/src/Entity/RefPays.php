<?php

namespace App\Entity;

use App\Repository\RefPaysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: 'ref_pays')]
#[ORM\Entity(repositoryClass: RefPaysRepository::class)]
#[ORM\Index(name: "pk__ref_pays", columns: ["uuid"])]
#[ORM\Index(name: 'idx__ref_pays__id_sir_libelle_min_maj', columns: ["id_pays_sir", "libelle_pays_min", "libelle_pays_maj"])]
#[ORM\Index(name: 'idx__ref_pays__archivage', columns: ["archivage"])]
class RefPays
{
    #[ORM\Id]
    #[ORM\Column(name: 'uuid', type: 'uuid', nullable: false)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $uuid = null;

    #[ORM\Column(name: 'id_pays_sir', type: Types::TEXT, nullable: true)]
    private ?string $idPaysSir = null;

    #[ORM\Column(name: 'libelle_pays_min', type: Types::TEXT, nullable: true)]
    private ?string $libellePaysMin = null;

    #[ORM\Column(name: 'libelle_pays_maj', type: Types::TEXT, nullable: true)]
    private ?string $libellePaysMaj = null;

    #[ORM\Column(name: 'code_iso_3', type: Types::TEXT, nullable: true)]
    private ?string $codeIso3 = null;

    #[ORM\Column(name: 'nationalite', type: Types::TEXT, nullable: true)]
    private ?string $nationalite = null;

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

    #[ORM\OneToMany(targetEntity: RefRegion::class, mappedBy: 'refPays')]
    private Collection $refRegions;

    #[ORM\OneToMany(targetEntity: RefCommune::class, mappedBy: 'RefPays')]
    private Collection $refCommunes;

    public function __construct()
    {
        $this->refRegions = new ArrayCollection();
        $this->refCommunes = new ArrayCollection();
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getIdPaysSir(): ?string
    {
        return $this->idPaysSir;
    }

    public function setIdPaysSir(?string $idPaysSir): static
    {
        $this->idPaysSir = $idPaysSir;

        return $this;
    }

    public function getLibellePaysMin(): ?string
    {
        return $this->libellePaysMin;
    }

    public function setLibellePaysMin(?string $libellePaysMin): static
    {
        $this->libellePaysMin = $libellePaysMin;

        return $this;
    }

    public function getLibellePaysMaj(): ?string
    {
        return $this->libellePaysMaj;
    }

    public function setLibellePaysMaj(?string $libellePaysMaj): static
    {
        $this->libellePaysMaj = $libellePaysMaj;

        return $this;
    }

    public function getCodeIso3(): ?string
    {
        return $this->codeIso3;
    }

    public function setCodeIso3(?string $codeIso3): static
    {
        $this->codeIso3 = $codeIso3;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(?string $nationalite): static
    {
        $this->nationalite = $nationalite;

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


    /**
     * @return Collection<int, RefRegion>
     */
    public function getRefRegions(): Collection
    {
        return $this->refRegions;
    }

    public function addRefRegion(RefRegion $refRegion): static
    {
        if (!$this->refRegions->contains($refRegion)) {
            $this->refRegions->add($refRegion);
            $refRegion->setRefPays($this);
        }

        return $this;
    }

    public function removeRefRegion(RefRegion $refRegion): static
    {
        if ($this->refRegions->removeElement($refRegion)) {
            // set the owning side to null (unless already changed)
            if ($refRegion->getRefPays() === $this) {
                $refRegion->setRefPays(null);
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
            $refCommune->setRefPays($this);
        }

        return $this;
    }

    public function removeRefCommune(RefCommune $refCommune): static
    {
        if ($this->refCommunes->removeElement($refCommune)) {
            // set the owning side to null (unless already changed)
            if ($refCommune->getRefPays() === $this) {
                $refCommune->setRefPays(null);
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
