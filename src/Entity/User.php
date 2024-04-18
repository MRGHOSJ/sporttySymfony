<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name:"user")]


#[UniqueEntity(fields: ['email'], message: 'Email already exists')]
class User implements UserInterface
{
    #[ORM\Column(name:"id", type:"integer", nullable:false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"IDENTITY")]
    private $id;

    #[ORM\Column(name:"nom", type:"string", length:255, nullable:false)]
    #[Assert\NotBlank(message: "This value should not be blank.")]
#[Assert\Length(min: 2, minMessage: "The Firstname must contain at least {{ limit }} characters.")]
    private $nom;
  
   

    #[ORM\Column(name:"prenom", type:"string", length:255, nullable:false)]

    #[Assert\NotBlank(message: "This value should not be blank.")]
#[Assert\Length(min: 2, minMessage: "The Lastname must contain at least {{ limit }} characters.")]
    private $prenom;

    #[Assert\Email]
    #[ORM\Column(name: 'email', type: 'string', length: 255, unique: true)]
   
    //#[ORM\Column(name:"email", type:"string", length:255, nullable:false)]
    private $email;

    #[ORM\Column(name:"password", type:"string", length:255, nullable:false)]
  #[Assert\NotBlank(message: "This value should not be blank.")]
    private $password;

    #[ORM\Column(name:"role", type:"string", length:255, nullable:true)]
    private $role;

    #[ORM\Column(name:"image_user", type:"string", length:255, nullable:true)]
    private $imageUser;

    #[ORM\OneToMany(targetEntity: AbonnementUtilisateur::class, mappedBy: "utilisateur", cascade: ["persist"])]
private $abonnements;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idAbonnement = new \Doctrine\Common\Collections\ArrayCollection();
    }
     /**
     * @return Collection|AbonnementUtilisateur[]
     */
    public function getAbonnements(): Collection
    {
        return $this->abonnements;
    }

    public function addAbonnement(AbonnementUtilisateur $abonnement): self
    {
        if (!$this->abonnements->contains($abonnement)) {
            $this->abonnements[] = $abonnement;
            $abonnement->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAbonnement(AbonnementUtilisateur $abonnement): self
    {
        if ($this->abonnements->removeElement($abonnement)) {
            // set the owning side to null (unless already changed)
            if ($abonnement->getUtilisateur() === $this) {
                $abonnement->setUtilisateur(null);
            }
        }

        return $this;
    }
    public function getUsername(): string
    {
        return $this->email;
    }

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }
    public function getRoles(): array
    {
        return [$this->role];
    }
    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getImageUser(): ?string
    {
        return $this->imageUser;
    }

    public function setImageUser(?string $imageUser): static
    {
        $this->imageUser = $imageUser;

        return $this;
    }

    /**
     * @return Collection<int, Abonnement>
     */
    public function getIdAbonnement(): Collection
    {
        return $this->idAbonnement;
    }

    public function addIdAbonnement(Abonnement $idAbonnement): static
    {
        if (!$this->idAbonnement->contains($idAbonnement)) {
            $this->idAbonnement->add($idAbonnement);
            $idAbonnement->addIdUtilisateur($this);
        }

        return $this;
    }

    public function removeIdAbonnement(Abonnement $idAbonnement): static
    {
        if ($this->idAbonnement->removeElement($idAbonnement)) {
            $idAbonnement->removeIdUtilisateur($this);
        }

        return $this;
    }
    public function getSalt(): ?string
    {
        // Vous pouvez retourner null si vous n'utilisez pas de sel pour le hachage du mot de passe
        return null;
    }

    public function eraseCredentials()
    {
        // Supprimer les informations sensibles du mot de passe
        // Cette méthode est appelée après que l'authentification ait eu lieu pour effacer les informations sensibles.
    }


}
