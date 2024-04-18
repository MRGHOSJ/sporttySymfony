<?php 
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AbonnementRepository;

#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
#[ORM\Table(name: "abonnement")]
class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "type", type: "string", length: 255, nullable: false)]
    private $type;

    #[ORM\Column(name: "prix", type: "float", nullable: false)]
    private $prix;

    #[ORM\Column(name: "description", type: "string", length: 255, nullable: false)]
    private $description;

    #[ORM\OneToMany(targetEntity: AbonnementUtilisateur::class, mappedBy: "abonnement")]
    private $utilisateurs;

    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getPrix(): ?float
    {
        return $this->prix;
    }
    
    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
    
        return $this;
    }
    public function getType(): ?string
{
    return $this->type;
}
public function getDescription(): ?string
{
    return $this->description;
}

public function setDescription(string $description): self
{
    $this->description = $description;

    return $this;
}
public function setType(string $type): self
{
    $this->type = $type;

    return $this;
}

    /**
     * @return Collection|AbonnementUtilisateur[]
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(AbonnementUtilisateur $utilisateur): self
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs[] = $utilisateur;
            $utilisateur->setAbonnement($this);
        }

        return $this;
    }

    public function removeUtilisateur(AbonnementUtilisateur $utilisateur): self
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            // set the owning side to null (unless already changed)
            if ($utilisateur->getAbonnement() === $this) {
                $utilisateur->setAbonnement(null);
            }
        }

        return $this;
    }

}
