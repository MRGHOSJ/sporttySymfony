<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MaterielRepository;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
#[ORM\Table(name: "materiel")]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: false)]
    private $nom;

    #[ORM\Column(name: "categorie", type: "string", length: 255, nullable: false)]
    private $categorie;

    #[ORM\Column(name: "qte", type: "integer", nullable: false)]
    private $qte;

    #[ORM\Column(name: "image", type: "string", length: 255, nullable: false)]
    private $image;

    #[ORM\Column(name: "video", type: "string", length: 255, nullable: false)]
    private $video;

    #[ORM\ManyToOne(targetEntity: "Stock")]
    private $idStock;

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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): static
    {
        $this->qte = $qte;

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

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): static
    {
        $this->video = $video;

        return $this;
    }

    public function getIdStock(): ?Stock
    {
        return $this->idStock;
    }

    public function setIdStock(?Stock $idStock): static
    {
        $this->idStock = $idStock;

        return $this;
    }

}
