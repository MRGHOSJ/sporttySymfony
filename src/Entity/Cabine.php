<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
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
    #[Assert\NotBlank]
    #[Assert\Regex(pattern:"/^[A-Z]/",message:"Le nom de la cabine doit commencer par une lettre majuscule.")]
    private $nomCabine;

    #[ORM\Column(name: "capacite", type: "integer", nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Range(min:0,minMessage:"La capacité doit être un nombre positif ou nul.")]
    private $capacite;

    #[ORM\Column(name: "has_vr", type: "boolean", nullable: false)]
    private $hasVr;

    #[ORM\Column(name: "image", type: "string", length: 255, nullable: true)]
    private $image;

    #[ORM\ManyToOne(targetEntity: "SaleDeSport")]
    #[ORM\JoinColumn(name:"id_salle", referencedColumnName:"id_salle", nullable:false)]
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
