<?php

namespace App\Entity;

use App\Repository\CommandeLivreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeLivreRepository::class)]
class CommandeLivre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'CommandeLivre')]
    private ?Commande $commande = null;

    #[ORM\ManyToOne(inversedBy: 'commandeLivres')]
    private ?Livre $Livre = null;

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

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->Livre;
    }

    public function setLivre(?Livre $Livre): static
    {
        $this->Livre = $Livre;

        return $this;
    }
}
