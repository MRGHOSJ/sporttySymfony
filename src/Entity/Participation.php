<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ParticipationRepository;


#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
#[ORM\Table(name: "participation")]

class Participation
{
     #[ORM\Id]
     #[ORM\GeneratedValue(strategy:"IDENTITY")]
     #[ORM\Column(name:"id", type:"integer", nullable:false)]
    private $id;

   
     #[ORM\ManyToOne(targetEntity:"User")]
   
    #[ORM\JoinColumn(name:"user", referencedColumnName:"id")]
    private ?User $user = null;


     #[ORM\ManyToOne(targetEntity:"Evenements")]
    #[ORM\JoinColumn(name:"event", referencedColumnName:"id_event")]
     
    private ?Evenements $event;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function getEvenement(): ?Evenements
    {
        return $this->event;
    }
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
    public function setEvenement(?Evenements $evenement): static    
    {
        $this->event = $evenement;
        
        return $this;
    }
    public function hasParticipant(User $user, Evenements $event): bool
{
    return $this->getUser() === $user && $this->getEvenement() === $event;
}

}
