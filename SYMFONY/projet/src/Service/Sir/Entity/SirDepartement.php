<?php

namespace App\Service\Sir\Entity;

use App\Service\Sir\Repository\SirDepartementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'sir_departement')]
#[ORM\Entity(repositoryClass: SirDepartementRepository::class, readOnly: true)]
#[ORM\Index(columns: ["id_departement", "libelle_departement_min", "libelle_departement_maj"])]
class SirDepartement
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(name: 'id', type: Types::INTEGER, nullable: false, options: ['default' => "nextval('seq_sir_departement')"])]
    #[ORM\SequenceGenerator(sequenceName: 'seq_sir_departement', allocationSize: '1', initialValue: '1')]
    private ?int $id = null;

    #[ORM\Column(name: 'id_departement', type: Types::STRING, length: 255, nullable: false)]
    private ?string $idDepartement = null;

    #[ORM\Column(name: 'libelle_departement_min', type: Types::TEXT, nullable: true)]
    private ?string $libelleDepartementMin = null;

    #[ORM\Column(name: 'libelle_departement_maj', type: Types::TEXT, nullable: true)]
    private ?string $libelleDepartementMaj = null;

    #[ORM\Column(name: 'id_region', type: Types::STRING, length: 255, nullable: true)]
    private ?string $idRegion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDepartement(): ?string
    {
        return $this->idDepartement;
    }

    public function getLibelleDepartementMin(): ?string
    {
        return $this->libelleDepartementMin;
    }

    public function getLibelleDepartementMaj(): ?string
    {
        return $this->libelleDepartementMaj;
    }

    public function getIdRegion(): ?string
    {
        return $this->idRegion;
    }
}
