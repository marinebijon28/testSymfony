<?php

namespace App\Service\Sir\Entity;

use App\Service\Sir\Repository\SirRegionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'sir_region')]
#[ORM\Entity(repositoryClass: SirRegionRepository::class, readOnly: true)]
#[ORM\Index(columns: ["id_region", "libelle_region_min", "libelle_region_maj"])]
class SirRegion
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(name: 'id', type: Types::INTEGER, nullable: false, options: ['default' => "nextval('seq_sir_region')"])]
    #[ORM\SequenceGenerator(sequenceName: 'seq_sir_region', allocationSize: '1', initialValue: '1')]
    private ?int $id = null;

    #[ORM\Column(name: 'id_region', type: Types::STRING, length: 255, nullable: false)]
    private ?string $idRegion = null;

    #[ORM\Column(name: 'libelle_region_min', type: Types::TEXT, nullable: true)]
    private ?string $libelleRegionMin = null;

    #[ORM\Column(name: 'libelle_region_maj', type: Types::TEXT, nullable: true)]
    private ?string $libelleRegionMaj = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRegion(): ?string
    {
        return $this->idRegion;
    }

    public function getLibelleRegionMin(): ?string
    {
        return $this->libelleRegionMin;
    }

    public function getLibelleRegionMaj(): ?string
    {
        return $this->libelleRegionMaj;
    }
}
