<?php

namespace PasqualePellicani\GeoLocalitaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_geo_metropolitana')]
class Metropolitana
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $denominazione;

    #[ORM\Column(type: 'string', length: 100)]
    private string $capoluogo;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $popolazione = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $superficie = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $densita = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $numero_comuni = null;

    // -- Collegamento al capoluogo reale come città (opzionale)
    #[ORM\ManyToOne(targetEntity: Citta::class)]
    #[ORM\JoinColumn(name: 'citta_capoluogo_id', referencedColumnName: 'id', nullable: true)]
    private ?Citta $cittaCapoluogo = null;

    // -- Collegamento alla provincia del capoluogo (opzionale)
    #[ORM\ManyToOne(targetEntity: Provincia::class)]
    #[ORM\JoinColumn(name: 'provincia_id', referencedColumnName: 'id', nullable: true)]
    private ?Provincia $provincia = null;

    // -- Collegamento alla regione (opzionale)
    #[ORM\ManyToOne(targetEntity: Regione::class)]
    #[ORM\JoinColumn(name: 'regione_id', referencedColumnName: 'id', nullable: true)]
    private ?Regione $regione = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getDenominazione(): string
    {
        return $this->denominazione;
    }
    public function setDenominazione(string $denominazione): self
    {
        $this->denominazione = $denominazione;
        return $this;
    }
    public function getCapoluogo(): string
    {
        return $this->capoluogo;
    }
    public function setCapoluogo(string $capoluogo): self
    {
        $this->capoluogo = $capoluogo;
        return $this;
    }
    public function getPopolazione(): ?int
    {
        return $this->popolazione;
    }
    public function setPopolazione(?int $popolazione): self
    {
        $this->popolazione = $popolazione;
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
    public function getDensita(): ?int
    {
        return $this->densita;
    }
    public function setDensita(?int $densita): self
    {
        $this->densita = $densita;
        return $this;
    }
    public function getNumeroComuni(): ?int
    {
        return $this->numero_comuni;
    }
    public function setNumeroComuni(?int $numero_comuni): self
    {
        $this->numero_comuni = $numero_comuni;
        return $this;
    }

    public function getCittaCapoluogo(): ?Citta
    {
        return $this->cittaCapoluogo;
    }
    public function setCittaCapoluogo(?Citta $citta): self
    {
        $this->cittaCapoluogo = $citta;
        return $this;
    }

    public function getProvincia(): ?Provincia
    {
        return $this->provincia;
    }
    public function setProvincia(?Provincia $provincia): self
    {
        $this->provincia = $provincia;
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
}
