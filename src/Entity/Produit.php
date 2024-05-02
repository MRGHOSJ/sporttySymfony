<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ProduitRepository;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\Table(name: "produit")]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[Assert\Regex(pattern: '/^[A-Z][a-zA-Z]*$/', message: 'The value must start with a capital letter')]
    private $nom;

    #[ORM\Column(name: "prix", type: "float", precision: 10, scale: 0, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(value: 0, message: 'The price must be greater than 0')]
    private $prix;

    #[ORM\Column(name: "qte", type: "integer", nullable: false)]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(value: 0, message: 'The quantity must be greater than 0')]
    private $qte;

    #[ORM\Column(name: "description", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank]
    private $description;

    #[ORM\Column(name: "categorie", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^[A-Z][a-zA-Z]*$/', message: 'The value must start with a capital letter')]
    private $categorie;

    #[ORM\Column(name: "image", type: "string", length: 255, nullable: false)]
    private $image;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

}
