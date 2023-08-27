<?php

namespace App\Entity;

use App\Entity\Livre;
use App\Entity\Commande;
use App\Entity\CommandeLivre;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeLivreRepository;

#[ORM\Entity(repositoryClass: CommandeLivreRepository::class)]
class CommandeLivre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'commandeLivres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $commande = null;

    #[ORM\ManyToOne(inversedBy: 'commandeLivres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Livre $livre = null;

    #[ORM\Column]
    private ?int $prix_unitaire = null;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): static
    {
        $this->livre = $livre;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }

    public function __toString() {  

        return $this->quantite. " ";                                               
    }

    public function getPrixUnitaire(): ?int
    {
        return $this->prix_unitaire;
    }

    public function setPrixUnitaire(int $prix_unitaire): static
    {
        $this->prix_unitaire = $prix_unitaire;

        return $this;
    } 

    // Créer un fonction pour calculer le sous-total et le total de la commande

    public function getSousTotal(): int
    {
       return  $this->prix_unitaire * $this->quantite; 
    }

    // public function getTotal(): int
    // {
    //    return  $this->prix_unitaire * $this->quantite; 
    // }
    ////////////////////////////////////////////////////////////////////////

}
