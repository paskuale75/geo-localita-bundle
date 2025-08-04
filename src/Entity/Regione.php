<?php

namespace PasqualePellicani\GeoLocalitaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_geo_regione')]
class Regione
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $nome;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $capoluogo = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $superficie = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $num_residenti = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $num_comuni = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $num_provincie = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $presidente = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $cod_istat = null;

    #[ORM\Column(type: 'string', length: 16, nullable: true)]
    private ?string $cod_fiscale = null;

    #[ORM\Column(type: 'string', length: 16, nullable: true)]
    private ?string $piva = null;

    #[ORM\Column(type: 'string', length: 200, nullable: true)]
    private ?string $pec = null;

    #[ORM\Column(type: 'string', length: 200, nullable: true)]
    private ?string $sito = null;

    #[ORM\Column(type: 'string', length: 200, nullable: true)]
    private ?string $sede = null;

    #[ORM\OneToMany(mappedBy: 'regione', targetEntity: Provincia::class, cascade: ['persist', 'remove'])]
    private Collection $province;

    #[ORM\OneToMany(mappedBy: 'regione', targetEntity: Citta::class, cascade: ['persist', 'remove'])]
    private Collection $citta;

    public function __construct()
    {
        $this->province = new ArrayCollection();
        $this->citta = new ArrayCollection();
    }

    // ... Getter & Setter (vedi sotto per copia/incolla) ...

    public function getId(): ?int
    {
        return $this->id;
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
    public function getCapoluogo(): ?string
    {
        return $this->capoluogo;
    }
    public function setCapoluogo(?string $capoluogo): self
    {
        $this->capoluogo = $capoluogo;
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
    public function getNumResidenti(): ?int
    {
        return $this->num_residenti;
    }
    public function setNumResidenti(?int $num_residenti): self
    {
        $this->num_residenti = $num_residenti;
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
    public function getNumProvincie(): ?int
    {
        return $this->num_provincie;
    }
    public function setNumProvincie(?int $num_provincie): self
    {
        $this->num_provincie = $num_provincie;
        return $this;
    }
    public function getPresidente(): ?string
    {
        return $this->presidente;
    }
    public function setPresidente(?string $presidente): self
    {
        $this->presidente = $presidente;
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
    public function getCodFiscale(): ?string
    {
        return $this->cod_fiscale;
    }
    public function setCodFiscale(?string $cod_fiscale): self
    {
        $this->cod_fiscale = $cod_fiscale;
        return $this;
    }
    public function getPiva(): ?string
    {
        return $this->piva;
    }
    public function setPiva(?string $piva): self
    {
        $this->piva = $piva;
        return $this;
    }
    public function getPec(): ?string
    {
        return $this->pec;
    }
    public function setPec(?string $pec): self
    {
        $this->pec = $pec;
        return $this;
    }
    public function getSito(): ?string
    {
        return $this->sito;
    }
    public function setSito(?string $sito): self
    {
        $this->sito = $sito;
        return $this;
    }
    public function getSede(): ?string
    {
        return $this->sede;
    }
    public function setSede(?string $sede): self
    {
        $this->sede = $sede;
        return $this;
    }

    /** @return Collection|Provincia[] */
    public function getProvince(): Collection
    {
        return $this->province;
    }
    public function addProvincia(Provincia $provincia): self
    {
        if (!$this->province->contains($provincia)) {
            $this->province[] = $provincia;
            $provincia->setRegione($this);
        }
        return $this;
    }
    public function removeProvincia(Provincia $provincia): self
    {
        if ($this->province->removeElement($provincia)) {
            if ($provincia->getRegione() === $this) {
                //$provincia->setRegione(null);
            }
        }
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
            $citta->setRegione($this);
        }
        return $this;
    }
    public function removeCitta(Citta $citta): self
    {
        if ($this->citta->removeElement($citta)) {
            if ($citta->getRegione() === $this) {
                //$citta->setRegione(null);
            }
        }
        return $this;
    }
}
