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

    #[ORM\Column(name: "prix", type: "float", precision: 10, scale: 0, nullable: false)]
    private $prix;

    #[ORM\Column(name: "description", type: "string", length: 255, nullable: false)]
    private $description;

    #[ORM\ManyToOne(targetEntity: "User")]
    private $idUser;

    #[ORM\ManyToMany(targetEntity: "User", mappedBy: "idAbonnement")]
    private $idUtilisateur;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idUtilisateur = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getIdUtilisateur(): Collection
    {
        return $this->idUtilisateur;
    }

    public function addIdUtilisateur(User $idUtilisateur): static
    {
        if (!$this->idUtilisateur->contains($idUtilisateur)) {
            $this->idUtilisateur->add($idUtilisateur);
            $idUtilisateur->addIdAbonnement($this);
        }

        return $this;
    }

    public function removeIdUtilisateur(User $idUtilisateur): static
    {
        if ($this->idUtilisateur->removeElement($idUtilisateur)) {
            $idUtilisateur->removeIdAbonnement($this);
        }

        return $this;
    }

}
