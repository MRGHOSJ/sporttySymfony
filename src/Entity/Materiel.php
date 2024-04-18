<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MaterielRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
#[ORM\Table(name: "materiel")]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    #[Assert\Regex(
        pattern: "/^[A-Z]/",
        message: "Le nom de la cabine doit commencer par une lettre majuscule."
    )]
    private $nom;

    #[ORM\Column(name: "categorie", type: "string", length: 255, nullable: false)]
    private $categorie;

    #[ORM\Column(name: "qte", type: "integer", nullable: false)]
    #[Assert\NotBlank(message: "La quantité ne peut pas être vide.")]
    #[Assert\Type(
        type: "integer",
        message: "La quantité doit être un nombre."
    )]
    #[Assert\GreaterThanOrEqual(
        value: 0,
        message: "La quantité doit être un nombre positif ou nul."
    )]
    private $qte;

    #[ORM\Column(name: "image", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "L'image ne peut pas être vide.")]
    private $image;

    #[ORM\Column(name: "video", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "La vidéo ne peut pas être vide.")]
    private $video;

    #[ORM\ManyToOne(targetEntity: "Stock")]
    #[ORM\JoinColumn(name: "id_stock")]
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
