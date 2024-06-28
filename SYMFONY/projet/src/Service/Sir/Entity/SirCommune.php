<?php

namespace App\Service\Sir\Entity;

use App\Service\Sir\Repository\SirCommuneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'sir_commune')]
#[ORM\Entity(repositoryClass: SirCommuneRepository::class, readOnly: true)]
#[ORM\Index(columns: ["id_region", "id_departement", "libelle_commune_min", "libelle_commune_maj", "id_commune"])]
class SirCommune
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(name: 'id', type: Types::INTEGER, nullable: false, options: ['default' => "nextval('seq_sir_commune')"])]
    #[ORM\SequenceGenerator(sequenceName: 'seq_sir_commune', allocationSize: '1', initialValue: '1')]
    private ?int $id = null;

    #[ORM\Column(name: 'id_commune', type: Types::STRING, length: 255, nullable: false)]
    private ?string $idCommune = null;

    #[ORM\Column(name: 'libelle_commune_min', type: Types::STRING, length: 255, nullable: true)]
    private ?string $libelleCommuneMin = null;

    #[ORM\Column(name: 'libelle_commune_maj', type: Types::STRING, length: 255, nullable: true)]
    private ?string $libelleCommuneMaj = null;

    #[ORM\Column(name: 'id_departement', type: Types::STRING, length: 255, nullable: true)]
    private ?string $idDepartement = null;

    #[ORM\Column(name: 'id_region', type: Types::STRING, length: 255, nullable: true)]
    private ?string $idRegion = null;

    #[ORM\Column(name: 'actual', type: Types::INTEGER, nullable: true)]
    private ?string $actual = null;

    #[ORM\Column(name: 'epsg4326_lat', type: Types::STRING, length: 255, nullable: true)]
    private ?string $epsg4326Lat = null;

    #[ORM\Column(name: 'epsg4326_long', type: Types::STRING, length: 255, nullable: true)]
    private ?string $epsg4326Long = null;

    #[ORM\Column(name: 'codes_postaux', type: Types::STRING, length: 255, nullable: true)]
    private ?string $codesPostaux = null;

    #[ORM\Column(name: 'id_commune_actuel', type: Types::STRING, length: 255, nullable: true)]
    private ?string $idCommuneActuel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCommune(): ?string
    {
        return $this->idCommune;
    }

    public function getLibelleCommuneMin(): ?string
    {
        return $this->libelleCommuneMin;
    }

    public function getLibelleCommuneMaj(): ?string
    {
        return $this->libelleCommuneMaj;
    }

    public function getIdDepartement(): ?string
    {
        return $this->idDepartement;
    }

    public function getIdRegion(): ?string
    {
        return $this->idRegion;
    }

    public function getActual(): ?int
    {
        return $this->actual;
    }

    public function getEpsg4326Lat(): ?string
    {
        return $this->epsg4326Lat;
    }

    public function getCodesPostaux(): ?string
    {
        return $this->codesPostaux;
    }

    public function getEpsg4326Long(): ?string
    {
        return $this->epsg4326Long;
    }

    public function getIdCommuneActuel(): ?string
    {
        return $this->idCommuneActuel;
    }
}
