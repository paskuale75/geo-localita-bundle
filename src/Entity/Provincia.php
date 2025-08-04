<?php

namespace PasqualePellicani\GeoLocalitaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_geo_provincia')]
class Provincia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 2)]
    private string $sigla;

    #[ORM\Column(type: 'string', length: 100)]
    private string $nome;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $superficie = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $residenti = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $num_comuni = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $id_regione = null;

    #[ORM\Column(type: 'string', length: 16, nullable: true)]
    private ?string $cod_istat = null;

    #[ORM\ManyToOne(targetEntity: Regione::class, inversedBy: 'province')]
    #[ORM\JoinColumn(name: 'regione_id', referencedColumnName: 'id')]
    private ?Regione $regione = null;

    #[ORM\OneToMany(mappedBy: 'provincia', targetEntity: Citta::class, cascade: ['persist', 'remove'])]
    private Collection $citta;

    public function __construct()
    {
        $this->citta = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getSigla(): string
    {
        return $this->sigla;
    }
    public function setSigla(string $sigla): self
    {
        $this->sigla = $sigla;
        return $this;
    }
    public function getNome(): string
    {
        return $this->nome;
    }
    public function setNome(string $nome): self
    {
        $this->nome = $nome;
        return $this;
    }
    public function getSuperficie(): ?float
    {
        return $this->superficie;
    }
    public function setSuperficie(?float $superficie): self
    {
        $this->superficie = $superficie;
        return $this;
    }
    public function getResidenti(): ?int
    {
        return $this->residenti;
    }
    public function setResidenti(?int $residenti): self
    {
        $this->residenti = $residenti;
        return $this;
    }
    public function getNumComuni(): ?int
    {
        return $this->num_comuni;
    }
    public function setNumComuni(?int $num_comuni): self
    {
        $this->num_comuni = $num_comuni;
        return $this;
    }
    public function getIdRegione(): ?int
    {
        return $this->id_regione;
    }
    public function setIdRegione(?int $id_regione): self
    {
        $this->id_regione = $id_regione;
        return $this;
    }
    public function getCodIstat(): ?string
    {
        return $this->cod_istat;
    }
    public function setCodIstat(?string $cod_istat): self
    {
        $this->cod_istat = $cod_istat;
        return $this;
    }

    public function getRegione(): ?Regione
    {
        return $this->regione;
    }
    public function setRegione(?Regione $regione): self
    {
        $this->regione = $regione;
        return $this;
    }

    /** @return Collection|Citta[] */
    public function getCitta(): Collection
    {
        return $this->citta;
    }
    public function addCitta(Citta $citta): self
    {
        if (!$this->citta->contains($citta)) {
            $this->citta[] = $citta;
            $citta->setProvincia($this);
        }
        return $this;
    }
    public function removeCitta(Citta $citta): self
    {
        if ($this->citta->removeElement($citta)) {
            /*if ($citta->getProvincia() === $this) {
                $citta->setProvincia(null);
            }*/
        }
        return $this;
    }
}
