<?php

namespace App\Entity;

use App\Repository\AppLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: 'app_log')]
#[ORM\Entity(repositoryClass: AppLogRepository::class)]
class AppLog
{
    #[ORM\Id]
    #[ORM\Column(name: 'uuid', type: 'uuid', nullable: false)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $uuid = null;

    #[ORM\Column(name: 'date_heure_action', type: Types::DATETIMETZ_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $dateHeureAction = null;

    #[ORM\Column(name: 'microseconde', type: Types::INTEGER, nullable: false)]
    private ?int $microseconde = null;

    #[ORM\Column(name: 'id_anonyme', type: Types::TEXT, nullable: true)]
    private ?string $idAnonyme = null;

    #[ORM\Column(name: 'nigend', type: Types::TEXT, nullable: false)]
    private ?string $nigend = null;

    #[ORM\Column(name: 'personnel', type: Types::TEXT, nullable: false)]
    private ?string $personnel = null;

    #[ORM\Column(name: 'type_action', type: Types::TEXT, nullable: false)]
    private ?string $typeAction = null;

    #[ORM\Column(name: 'adresse', type: Types::TEXT, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(name: 'clef', type: Types::TEXT, nullable: true)]
    private ?string $clef = null;

    #[ORM\Column(name: 'nom_table', type: Types::TEXT, nullable: true)]
    private ?string $nomTable = null;

    #[ORM\Column(name: 'data', type: Types::TEXT, nullable: true)]
    private ?string $data = null;

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function getDateHeureAction(): ?\DateTimeInterface
    {
        return $this->dateHeureAction;
    }

    public function setDateHeureAction(\DateTimeInterface $dateHeureAction): static
    {
        $this->dateHeureAction = $dateHeureAction;

        return $this;
    }

    public function getMicroseconde(): ?int
    {
        return $this->microseconde;
    }

    public function setMicroseconde(int $microseconde): static
    {
        $this->microseconde = $microseconde;

        return $this;
    }

    public function getIdAnonyme(): ?string
    {
        return $this->idAnonyme;
    }

    public function setIdAnonyme(?string $idAnonyme): static
    {
        $this->idAnonyme = $idAnonyme;

        return $this;
    }

    public function getNigend(): ?string
    {
        return $this->nigend;
    }

    public function setNigend(string $nigend): static
    {
        $this->nigend = $nigend;

        return $this;
    }

    public function getPersonnel(): ?string
    {
        return $this->personnel;
    }

    public function setPersonnel(string $personnel): static
    {
        $this->personnel = $personnel;

        return $this;
    }

    public function getTypeAction(): ?string
    {
        return $this->typeAction;
    }

    public function setTypeAction(string $typeAction): static
    {
        $this->typeAction = $typeAction;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getClef(): ?string
    {
        return $this->clef;
    }

    public function setClef(?string $clef): static
    {
        $this->clef = $clef;

        return $this;
    }

    public function getNomTable(): ?string
    {
        return $this->nomTable;
    }

    public function setNomTable(?string $nomTable): static
    {
        $this->nomTable = $nomTable;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): static
    {
        $this->data = $data;

        return $this;
    }
}
