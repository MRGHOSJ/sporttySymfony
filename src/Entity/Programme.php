<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProgrammeRepository;


#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
#[ORM\Table(name: "programme")]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: false)]
    private $nom;

    #[ORM\Column(name: "description", type: "string", length: 255, nullable: false)]
    private $description;

    #[ORM\Column(name: "duree", type: "integer", nullable: false)]
    private $duree;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

}
