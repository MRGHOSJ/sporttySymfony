<?php 
namespace App\Entity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AbonnementRepository;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
#[ORM\Table(name: "abonnement")]


#[UniqueEntity(fields: ['type'], message: 'Type already exists')]
class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;


 
    #[ORM\Column(name: "type", type: "string", length: 255, unique: true)]
    private $type;

    #[ORM\Column(name: "prix", type: "float", nullable: false)]
    #[Assert\NotBlank(message: "Le prix ne peut pas être vide.")]
#[Assert\GreaterThan(value: 0, message: "Le prix doit être supérieur à zéro.")]
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
    public function hasSubscription(Abonnement $abonnement): bool
    {
        foreach ($this->abonnements as $abonnementUtilisateur) {
            if ($abonnementUtilisateur->getAbonnement() === $abonnement) {
                return true;
            }
        }

        return false;
    }
}
