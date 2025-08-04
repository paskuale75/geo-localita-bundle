<?php

namespace PasqualePellicani\GeoLocalitaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_geo_coordinate')]
class Coordinate
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: Citta::class, inversedBy: 'coordinate')]
    #[ORM\JoinColumn(name: 'citta_id', referencedColumnName: 'id', nullable: false)]
    private ?Citta $citta = null;

    #[ORM\Column(type: 'float')]
    private float $lng;

    #[ORM\Column(type: 'float')]
    private float $lat;

    public function getCitta(): ?Citta
    {
        return $this->citta;
    }
    public function setCitta(?Citta $citta): self
    {
        $this->citta = $citta;
        return $this;
    }
    public function getLng(): float
    {
        return $this->lng;
    }
    public function setLng(float $lng): self
    {
        $this->lng = $lng;
        return $this;
    }
    public function getLat(): float
    {
        return $this->lat;
    }
    public function setLat(float $lat): self
    {
        $this->lat = $lat;
        return $this;
    }
}
