<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CoursRepository;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
#[ORM\Table(name: "cours")]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_cours", type: "integer", nullable: false)]
    private $idCours;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: false)]
    private $nom;

    #[ORM\Column(name: "coach", type: "string", length: 255, nullable: false)]
    private $coach;

    #[ORM\Column(name: "jours", type: "string", length: 255, nullable: false)]
    private $jours;

    #[ORM\Column(name: "duree", type: "integer", nullable: false)]
    private $duree;

    #[ORM\Column(name: "type", type: "string", length: 255, nullable: false)]
    private $type;

    #[ORM\Column(name: "prix", type: "integer", nullable: false)]
    private $prix;

    #[ORM\Column(name: "image", type: "string", length: 255, nullable: false)]
    private $image;

    #[ORM\Column(name: "lienVideo", type: "string", length: 255, nullable: false)]
    private $lienvideo;

    #[ORM\ManyToOne(targetEntity: "Programme")]
    private $idProgramme;

    public function getIdCours(): ?int
    {
        return $this->idCours;
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

    public function getCoach(): ?string
    {
        return $this->coach;
    }

    public function setCoach(string $coach): static
    {
        $this->coach = $coach;

        return $this;
    }

    public function getJours(): ?string
    {
        return $this->jours;
    }

    public function setJours(string $jours): static
    {
        $this->jours = $jours;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

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

    public function getLienvideo(): ?string
    {
        return $this->lienvideo;
    }

    public function setLienvideo(string $lienvideo): static
    {
        $this->lienvideo = $lienvideo;

        return $this;
    }

    public function getIdProgramme(): ?Programme
    {
        return $this->idProgramme;
    }

    public function setIdProgramme(?Programme $idProgramme): static
    {
        $this->idProgramme = $idProgramme;

        return $this;
    }

}
