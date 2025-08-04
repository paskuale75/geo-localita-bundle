<?php

namespace PasqualePellicani\GeoLocalitaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_geo_cap')]
class Cap
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Citta::class, inversedBy: "cap")]
    #[ORM\JoinColumn(name: "citta_id", referencedColumnName: "id", nullable: false)]
    private ?Citta $citta = null;

    #[ORM\Column(type: 'string', length: 16)]
    private string $cap;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getCitta(): ?Citta
    {
        return $this->citta;
    }
    public function setCitta(?Citta $citta): self
    {
        $this->citta = $citta;
        return $this;
    }
    public function getCap(): string
    {
        return $this->cap;
    }
    public function setCap(string $cap): self
    {
        $this->cap = $cap;
        return $this;
    }
}
