<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReclamationRepository;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
#[ORM\Table(name: "reclamation")]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: false)]
    private $nom;

    #[ORM\Column(name: "id_user", type: "integer", nullable: true)]
    private $idUser;

    #[ORM\Column(name: "description", type: "string", length: 255, nullable: false)]
    private $description;

    #[ORM\Column(name: "date", type: "date", nullable: true)]
    private $date;

    #[ORM\Column(name: "nbr_etoile", type: "string", length: 255, nullable: false)]
    private $nbrEtoile;

    #[ORM\Column(name: "reponse", type: "string", length: 255, nullable: true)]
    private $reponse;

    #[ORM\Column(name: "statut", type: "string", length: 255, nullable: false)]
    private $statut;

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

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(?int $idUser): static
    {
        $this->idUser = $idUser;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getNbrEtoile(): ?string
    {
        return $this->nbrEtoile;
    }

    public function setNbrEtoile(string $nbrEtoile): static
    {
        $this->nbrEtoile = $nbrEtoile;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(?string $reponse): static
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

}
