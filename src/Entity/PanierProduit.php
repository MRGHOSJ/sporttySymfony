<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PanierProduitRepository;

#[ORM\Entity(repositoryClass: PanierProduitRepository::class)]
#[ORM\Table(name: "panier_produit")]
class PanierProduit
{
    #[ORM\Id]
    #[ORM\Column(name: "panier_id", type: "integer", nullable: false)]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private $panierId;

    #[ORM\Id]
    #[ORM\Column(name: "produit_id", type: "integer", nullable: false)]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private $produitId;

    #[ORM\Column(name: "quantite", type: "integer", nullable: false)]
    private $quantite;

    public function getPanierId(): ?int
    {
        return $this->panierId;
    }

    public function getProduitId(): ?int
    {
        return $this->produitId;
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
}
