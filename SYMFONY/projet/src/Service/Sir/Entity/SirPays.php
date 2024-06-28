<?php

namespace App\Service\Sir\Entity;

use App\Service\Sir\Repository\SirPaysRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'sir_pays')]
#[ORM\Entity(repositoryClass: SirPaysRepository::class, readOnly: true)]
#[ORM\Index(columns: ["id_pays", "libelle_pays_min", "libelle_pays_maj", "code_iso_3"])]
class SirPays
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(name: 'id', type: Types::INTEGER, nullable: false, options: ['default' => "nextval('seq_sir_pays')"])]
    #[ORM\SequenceGenerator(sequenceName: 'seq_sir_pays', allocationSize: '1', initialValue: '1')]
    private ?int $id = null;

    #[ORM\Column(name: 'id_pays', type: Types::STRING, length: 255, nullable: false)]
    private ?string $idPays = null;

    #[ORM\Column(name: 'libelle_pays_min', type: Types::TEXT, nullable: true)]
    private ?string $libellePaysMin = null;

    #[ORM\Column(name: 'libelle_pays_maj', type: Types::TEXT, nullable: true)]
    private ?string $libellePaysMaj = null;

    #[ORM\Column(name: 'code_iso_numerique', type: Types::STRING, length: 255, nullable: true)]
    private ?string $codeIsoNumerique = null;

    #[ORM\Column(name: 'code_iso_2', type: Types::STRING, length: 255, nullable: true)]
    private ?string $codeIso2 = null;

    #[ORM\Column(name: 'code_iso_3', type: Types::STRING, length: 255, nullable: true)]
    private ?string $codeIso3 = null;

    #[ORM\Column(name: 'libelle_long_pays_min', type: Types::TEXT, nullable: true)]
    private ?string $libelleLongPaysMin = null;

    #[ORM\Column(name: 'libelle_long_pays_maj', type: Types::TEXT, nullable: true)]
    private ?string $libelleLongPaysMaj = null;

    #[ORM\Column(name: 'nationalite', type: Types::STRING, length: 255, nullable: true)]
    private ?string $nationalite = null;

    #[ORM\Column(name: 'actual', type: Types::STRING, length: 255, nullable: true)]
    private ?string $actual = null;

    #[ORM\Column(name: 'schengen', type: Types::STRING, length: 255, nullable: true)]
    private ?string $schengen = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPays(): ?string
    {
        return $this->idPays;
    }

    public function getLibellePaysMin(): ?string
    {
        return $this->libellePaysMin;
    }

    public function getLibellePaysMaj(): ?string
    {
        return $this->libellePaysMaj;
    }

    public function getCodeIsoNumerique(): ?string
    {
        return $this->codeIsoNumerique;
    }

    public function getCodeIso2(): ?string
    {
        return $this->codeIso2;
    }

    public function getCodeIso3(): ?string
    {
        return $this->codeIso3;
    }

    public function getLibelleLongPaysMin(): ?string
    {
        return $this->libelleLongPaysMin;
    }

    public function getLibelleLongPaysMaj(): ?string
    {
        return $this->libelleLongPaysMaj;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function getActual(): ?string
    {
        return $this->actual;
    }

    public function getSchengen(): ?string
    {
        return $this->schengen;
    }
}
