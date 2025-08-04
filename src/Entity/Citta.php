<?php

namespace PasqualePellicani\GeoLocalitaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_geo_citta')]
class Citta
{

    public function __construct()
    {
        $this->cap = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 20, nullable: true, unique: true)]
    private ?string $istat = null;

    #[ORM\Column(type: 'integer', nullable: true, unique: true)]
    private ?int $geonameid = null; // Solo estero


    #[ORM\Column(type: 'string', length: 150)]
    private string $nome;


    #[ORM\ManyToOne(targetEntity: Nazione::class, inversedBy: "citta")]
    #[ORM\JoinColumn(name: "nazione_codice_iso2", referencedColumnName: "codice_iso2", nullable: true)]
    private ?Nazione $nazione = null;

    /**
     * La relazione Regione è stata resa nullable:true per via delle città estere
     */
    #[ORM\ManyToOne(targetEntity: Regione::class, inversedBy: 'citta')]
    #[ORM\JoinColumn(name: 'regione_id', referencedColumnName: 'id', nullable: true)]
    private Regione $regione;

    /**
     * La relazione Provincia è stata resa nullable:true per via delle città estere
     */
    #[ORM\ManyToOne(targetEntity: Provincia::class, inversedBy: 'citta')]
    #[ORM\JoinColumn(name: 'provincia_id', referencedColumnName: 'id', nullable: true)]
    private Provincia $provincia;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $prefisso = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $cod_fisco = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $num_residenti = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $superficie = null;

    #[ORM\Column(type: 'string', length: 16, nullable: true)]
    private ?string $cf = null;

    #[ORM\OneToMany(mappedBy: "citta", targetEntity: Cap::class, cascade: ["persist", "remove"])]
    private Collection $cap;

    #[ORM\OneToOne(mappedBy: 'citta', targetEntity: Coordinate::class, cascade: ['persist', 'remove'])]
    private ?Coordinate $coordinate = null;



    // --- Getters & Setters ---

    public function getIstat(): string
    {
        return $this->istat;
    }
    public function setIstat(string $istat): self
    {
        $this->istat = $istat;
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

    /**
     * Get the value of geonameid
     */
    public function getGeonameid()
    {
        return $this->geonameid;
    }

    /**
     * Set the value of geonameid
     *
     * @return  self
     */
    public function setGeonameid($geonameid)
    {
        $this->geonameid = $geonameid;
        return $this;
    }

    public function getNazione(): ?Nazione
    {
        return $this->nazione;
    }

    public function setNazione(?Nazione $nazione): self
    {
        $this->nazione = $nazione;
        return $this;
    }


    public function getRegione(): ?Regione
    {
        return $this->regione;
    }
    public function setRegione(Regione $regione): self
    {
        $this->regione = $regione;
        return $this;
    }

    public function getProvincia(): ?Provincia
    {
        return $this->provincia;
    }
    public function setProvincia(Provincia $provincia): self
    {
        $this->provincia = $provincia;
        return $this;
    }

    public function getPrefisso(): ?string
    {
        return $this->prefisso;
    }
    public function setPrefisso(?string $prefisso): self
    {
        $this->prefisso = $prefisso;
        return $this;
    }

    public function getCodFisco(): ?string
    {
        return $this->cod_fisco;
    }
    public function setCodFisco(?string $cod_fisco): self
    {
        $this->cod_fisco = $cod_fisco;
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

    public function getSuperficie(): ?float
    {
        return $this->superficie;
    }
    public function setSuperficie(?float $superficie): self
    {
        $this->superficie = $superficie;
        return $this;
    }

    public function getCf(): ?string
    {
        return $this->cf;
    }
    public function setCf(?string $cf): self
    {
        $this->cf = $cf;
        return $this;
    }

    /**
     * @return Collection|Cap[]
     */
    public function getCap(): Collection
    {
        return $this->cap;
    }

    public function addCap(Cap $cap): self
    {
        if (!$this->cap->contains($cap)) {
            $this->cap[] = $cap;
            $cap->setCitta($this);
        }
        return $this;
    }

    public function removeCap(Cap $cap): self
    {
        if ($this->cap->contains($cap)) {
            $this->cap->removeElement($cap);
            if ($cap->getCitta() === $this) {
                //$cap->setCitta(null);
            }
        }
        return $this;
    }

    public function getCoordinate(): ?Coordinate
    {
        return $this->coordinate;
    }
    public function setCoordinate(?Coordinate $coordinate): self
    {
        $this->coordinate = $coordinate;
        // aggiorna anche il lato Coordinate
        if ($coordinate && $coordinate->getCitta() !== $this) {
            $coordinate->setCitta($this);
        }
        return $this;
    }
}
