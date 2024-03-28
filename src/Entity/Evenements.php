<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EvenementsRepository;

#[ORM\Entity(repositoryClass: EvenementsRepository::class)]
#[ORM\Table(name: "evenements")]
class Evenements
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_event", type: "integer", nullable: false)]
    private $idEvent;

    #[ORM\Column(name: "nom_event", type: "string", length: 255, nullable: false)]
    private $nomEvent;

    #[ORM\Column(name: "descri_event", type: "string", length: 255, nullable: false)]
    private $descriEvent;

    #[ORM\Column(name: "categorie_event", type: "string", length: 255, nullable: false)]
    private $categorieEvent;

    #[ORM\Column(name: "date_event", type: "date", nullable: false)]
    private $dateEvent;

    #[ORM\Column(name: "lieu_event", type: "string", length: 255, nullable: false)]
    private $lieuEvent;

    #[ORM\Column(name: "nbr_par", type: "integer", nullable: false)]
    private $nbrPar;

    public function getIdEvent(): ?int
    {
        return $this->idEvent;
    }

    public function getNomEvent(): ?string
    {
        return $this->nomEvent;
    }

    public function setNomEvent(string $nomEvent): static
    {
        $this->nomEvent = $nomEvent;

        return $this;
    }

    public function getDescriEvent(): ?string
    {
        return $this->descriEvent;
    }

    public function setDescriEvent(string $descriEvent): static
    {
        $this->descriEvent = $descriEvent;

        return $this;
    }

    public function getCategorieEvent(): ?string
    {
        return $this->categorieEvent;
    }

    public function setCategorieEvent(string $categorieEvent): static
    {
        $this->categorieEvent = $categorieEvent;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->dateEvent;
    }

    public function setDateEvent(\DateTimeInterface $dateEvent): static
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    public function getLieuEvent(): ?string
    {
        return $this->lieuEvent;
    }

    public function setLieuEvent(string $lieuEvent): static
    {
        $this->lieuEvent = $lieuEvent;

        return $this;
    }

    public function getNbrPar(): ?int
    {
        return $this->nbrPar;
    }

    public function setNbrPar(int $nbrPar): static
    {
        $this->nbrPar = $nbrPar;

        return $this;
    }

}
