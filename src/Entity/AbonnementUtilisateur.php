<?php 
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "abonnement_utilisateur")]
class AbonnementUtilisateur
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "abonnements")]
    #[ORM\JoinColumn(name: "id_utilisateur", referencedColumnName: "id")]
    private $utilisateur;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Abonnement::class, inversedBy: "utilisateurs")]
    #[ORM\JoinColumn(name: "id_abonnement", referencedColumnName: "id")]
    private $abonnement;

    // Getters and setters...

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getAbonnement(): ?Abonnement
    {
        return $this->abonnement;
    }

    public function setAbonnement(?Abonnement $abonnement): self
    {
        $this->abonnement = $abonnement;

        return $this;
    }
}
