<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CabineRepository;

#[ORM\Entity(repositoryClass: CabineRepository::class)]
#[ORM\Table(name: "cabine")]
class Cabine
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "nom_cabine", type: "string", length: 255, nullable: false)]
    private $nomCabine;

    #[ORM\Column(name: "capacite", type: "integer", nullable: false)]
    private $capacite;

    #[ORM\Column(name: "has_vr", type: "boolean", nullable: false)]
    private $hasVr;

    #[ORM\Column(name: "image", type: "string", length: 255, nullable: false)]
    private $image;

    #[ORM\ManyToOne(targetEntity: "SaleDeSport")]
    private $idSalle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCabine(): ?string
    {
        return $this->nomCabine;
    }

    public function setNomCabine(string $nomCabine): static
    {
        $this->nomCabine = $nomCabine;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function isHasVr(): ?bool
    {
        return $this->hasVr;
    }

    public function setHasVr(bool $hasVr): static
    {
        $this->hasVr = $hasVr;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getIdSalle(): ?SaleDeSport
    {
        return $this->idSalle;
    }

    public function setIdSalle(?SaleDeSport $idSalle): static
    {
        $this->idSalle = $idSalle;

        return $this;
    }

}
