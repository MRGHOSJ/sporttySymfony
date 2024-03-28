<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SaleDeSportRepository;

#[ORM\Entity(repositoryClass: SaleDeSportRepository::class)]
#[ORM\Table(name: "sale_de_sport")]
class SaleDeSport
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_salle", type: "integer", nullable: false)]
    private $idSalle;

    #[ORM\Column(name: "nom_salle", type: "string", length: 255, nullable: false)]
    private $nomSalle;

    #[ORM\Column(name: "descr", type: "string", length: 255, nullable: false)]
    private $descr;

    #[ORM\Column(name: "lieu_salle", type: "string", length: 255, nullable: false)]
    private $lieuSalle;

    #[ORM\Column(name: "num_salle", type: "integer", nullable: false)]
    private $numSalle;

    #[ORM\Column(name: "lienVideo", type: "string", length: 255, nullable: false)]
    private $lienvideo;

    #[ORM\Column(name: "image", type: "string", length: 255, nullable: false)]
    private $image;

    #[ORM\Column(name: "location", type: "string", length: 255, nullable: false)]
    private $location;

    public function getIdSalle(): ?int
    {
        return $this->idSalle;
    }

    public function getNomSalle(): ?string
    {
        return $this->nomSalle;
    }

    public function setNomSalle(string $nomSalle): static
    {
        $this->nomSalle = $nomSalle;

        return $this;
    }

    public function getDescr(): ?string
    {
        return $this->descr;
    }

    public function setDescr(string $descr): static
    {
        $this->descr = $descr;

        return $this;
    }

    public function getLieuSalle(): ?string
    {
        return $this->lieuSalle;
    }

    public function setLieuSalle(string $lieuSalle): static
    {
        $this->lieuSalle = $lieuSalle;

        return $this;
    }

    public function getNumSalle(): ?int
    {
        return $this->numSalle;
    }

    public function setNumSalle(int $numSalle): static
    {
        $this->numSalle = $numSalle;

        return $this;
    }

    public function getLienvideo(): ?string
    {
        return $this->lienvideo;
    }

    public function setLienvideo(string $lienvideo): static
    {
        $this->lienvideo = $lienvideo;

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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

}
