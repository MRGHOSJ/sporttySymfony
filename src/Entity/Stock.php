<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\StockRepository;

#[ORM\Entity(repositoryClass: StockRepository::class)]
#[ORM\Table(name: "stock")]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: false)]
    private $nom;

    #[ORM\Column(name: "quantite", type: "integer", nullable: false)]
    private $quantite;

    #[ORM\Column(name: "lieu", type: "string", length: 255, nullable: false)]
    private $lieu;

    #[ORM\Column(name: "cordonnet", type: "string", length: 255, nullable: false)]
    private $cordonnet;

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

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getCordonnet(): ?string
    {
        return $this->cordonnet;
    }

    public function setCordonnet(string $cordonnet): static
    {
        $this->cordonnet = $cordonnet;

        return $this;
    }

}
