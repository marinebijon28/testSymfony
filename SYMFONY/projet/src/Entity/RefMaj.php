<?php

namespace App\Entity;

use App\Repository\RefMajRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RefMajRepository::class)]
class RefMaj
{
    #[ORM\Id]
    #[ORM\Column(name: 'uuid', type: 'uuid', nullable: false)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $uuid = null;

    #[ORM\Column(name: 'date_heure_debut', type: Types::DATETIMETZ_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $dateHeureDebut = null;

    #[ORM\Column(name: 'date_heure_fin', type: Types::DATETIMETZ_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $dateHeureFin = null;

    #[ORM\Column(name: 'duree', type: Types::TEXT, nullable: false)]
    private ?string $duree = null;

    #[ORM\Column(name: 'nom_table', type: Types::TEXT, nullable: false)]
    private ?string $nomTable = null;

    #[ORM\Column(name: 'nb_enregistrement_total', type: Types::TEXT, nullable: false)]
    private ?int $nbEnregistrementTotal = null;

    #[ORM\Column(name: 'nb_enregistrement_ajout', type: Types::TEXT, nullable: false)]
    private ?int $nbEnregistrementAjout = null;

    #[ORM\Column(name: 'nb_enregistrement_modification', type: Types::TEXT, nullable: false)]
    private ?int $nbEnregistrementModification = null;

    #[ORM\Column(name: 'nb_enregistrement_archivage', type: Types::TEXT, nullable: false)]
    private ?int $nbEnregistrementArchivage = null;

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): static
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDateHeureFin(): ?\DateTimeInterface
    {
        return $this->dateHeureFin;
    }

    public function setDateHeureFin(\DateTimeInterface $dateHeureFin): static
    {
        $this->dateHeureFin = $dateHeureFin;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getNomTable(): ?string
    {
        return $this->nomTable;
    }

    public function setNomTable(string $nomTable): static
    {
        $this->nomTable = $nomTable;

        return $this;
    }

    public function getNbEnregistrementTotal(): ?string
    {
        return $this->nbEnregistrementTotal;
    }

    public function setNbEnregistrementTotal(string $nbEnregistrementTotal): static
    {
        $this->nbEnregistrementTotal = $nbEnregistrementTotal;

        return $this;
    }

    public function getNbEnregistrementAjout(): ?string
    {
        return $this->nbEnregistrementAjout;
    }

    public function setNbEnregistrementAjout(string $nbEnregistrementAjout): static
    {
        $this->nbEnregistrementAjout = $nbEnregistrementAjout;

        return $this;
    }

    public function getNbEnregistrementModification(): ?string
    {
        return $this->nbEnregistrementModification;
    }

    public function setNbEnregistrementModification(string $nbEnregistrementModification): static
    {
        $this->nbEnregistrementModification = $nbEnregistrementModification;

        return $this;
    }

    public function getNbEnregistrementArchivage(): ?string
    {
        return $this->nbEnregistrementArchivage;
    }

    public function setNbEnregistrementArchivage(string $nbEnregistrementArchivage): static
    {
        $this->nbEnregistrementArchivage = $nbEnregistrementArchivage;

        return $this;
    }

}
