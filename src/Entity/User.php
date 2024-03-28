<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name:"user")]
class User
{
    #[ORM\Column(name:"id", type:"integer", nullable:false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"IDENTITY")]
    private $id;

    #[ORM\Column(name:"nom", type:"string", length:255, nullable:false)]
    private $nom;

    #[ORM\Column(name:"prenom", type:"string", length:255, nullable:false)]
    private $prenom;

    #[ORM\Column(name:"email", type:"string", length:255, nullable:false)]
    private $email;

    #[ORM\Column(name:"password", type:"string", length:255, nullable:false)]
    private $password;

    #[ORM\Column(name:"role", type:"string", length:255, nullable:true)]
    private $role;

    #[ORM\Column(name:"image_user", type:"string", length:255, nullable:true)]
    private $imageUser;

    #[ORM\ManyToMany(targetEntity:"Abonnement", mappedBy:"idUtilisateur")]
    private $idAbonnement = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idAbonnement = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function setRole(?string $role): static
    {
        $this->role = $role;

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

}
