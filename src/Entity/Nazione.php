<?php

namespace PasqualePellicani\GeoLocalitaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "tbl_geo_nazione")]
class Nazione
{
    #[ORM\Id]
    #[ORM\Column(name: "codice_iso2", type: "string", length: 2)]
    private string $codiceIso2;

    #[ORM\Column(type: "string", length: 3)]
    private string $codiceIso3;

    #[ORM\Column(type: "integer")]
    private int $codiceNumerico;

    #[ORM\Column(type: "string", length: 100)]
    private string $nome;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $capitale = null;

    #[ORM\Column(name: 'codice_z', type: 'string', length: 4, nullable: true, unique: true)]
    private ?string $codiceZ = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $area = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $popolazione = null;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $continente = null;

    #[ORM\Column(type: "string", length: 15, nullable: true)]
    private ?string $tld = null;

    #[ORM\Column(type: "string", length: 15, nullable: true)]
    private ?string $valuta = null;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $nomeValuta = null;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $prefissoTel = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $geonameId = null;

    #[ORM\OneToMany(mappedBy: "nazione", targetEntity: Citta::class, cascade: ["persist"], orphanRemoval: false)]
    private Collection $citta;

    public function __construct()
    {
        $this->citta = new ArrayCollection();
    }

    // === Getter & Setter ===

    public function getCodiceIso2(): string { return $this->codiceIso2; }
    public function setCodiceIso2(string $codiceIso2): self { $this->codiceIso2 = $codiceIso2; return $this; }

    public function getCodiceIso3(): string { return $this->codiceIso3; }
    public function setCodiceIso3(string $codiceIso3): self { $this->codiceIso3 = $codiceIso3; return $this; }

    public function getCodiceNumerico(): int { return $this->codiceNumerico; }
    public function setCodiceNumerico(int $codiceNumerico): self { $this->codiceNumerico = $codiceNumerico; return $this; }

    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): self { $this->nome = $nome; return $this; }

    public function getCapitale(): ?string { return $this->capitale; }
    public function setCapitale(?string $capitale): self { $this->capitale = $capitale; return $this; }

    public function getCodiceZ(): ?string{return $this->codiceZ;}

    public function setCodiceZ(?string $codiceZ): self
    {
        $this->codiceZ = $codiceZ ? strtoupper($codiceZ) : null; // es. Z404
        return $this;
    }

    public function getArea(): ?int { return $this->area; }
    public function setArea(?int $area): self { $this->area = $area; return $this; }

    public function getPopolazione(): ?int { return $this->popolazione; }
    public function setPopolazione(?int $popolazione): self { $this->popolazione = $popolazione; return $this; }

    public function getContinente(): ?string { return $this->continente; }
    public function setContinente(?string $continente): self { $this->continente = $continente; return $this; }

    public function getTld(): ?string { return $this->tld; }
    public function setTld(?string $tld): self { $this->tld = $tld; return $this; }

    public function getValuta(): ?string { return $this->valuta; }
    public function setValuta(?string $valuta): self { $this->valuta = $valuta; return $this; }

    public function getNomeValuta(): ?string { return $this->nomeValuta; }
    public function setNomeValuta(?string $nomeValuta): self { $this->nomeValuta = $nomeValuta; return $this; }

    public function getPrefissoTel(): ?string { return $this->prefissoTel; }
    public function setPrefissoTel(?string $prefissoTel): self { $this->prefissoTel = $prefissoTel; return $this; }

    public function getGeonameId(): ?int { return $this->geonameId; }
    public function setGeonameId(?int $geonameId): self { $this->geonameId = $geonameId; return $this; }

    /**
     * @return Collection|Citta[]
     */
    public function getCitta(): Collection
    {
        return $this->citta;
    }

    public function addCitta(Citta $citta): self
    {
        if (!$this->citta->contains($citta)) {
            $this->citta[] = $citta;
            $citta->setNazione($this);
        }
        return $this;
    }

    public function removeCitta(Citta $citta): self
    {
        if ($this->citta->removeElement($citta)) {
            if ($citta->getNazione() === $this) {
                $citta->setNazione(null);
            }
        }
        return $this;
    }
}
