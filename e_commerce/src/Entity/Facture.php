<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numero_facture = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_facture = null;

    #[ORM\ManyToOne(inversedBy: 'Facture')]
    private ?Commande $commande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroFacture(): ?int
    {
        return $this->numero_facture;
    }

    public function setNumeroFacture(int $numero_facture): static
    {
        $this->numero_facture = $numero_facture;

        return $this;
    }

    public function getDateFacture(): ?\DateTimeInterface
    {
        return $this->date_facture;
    }

    public function setDateFacture(\DateTimeInterface $date_facture): static
    {
        $this->date_facture = $date_facture;

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

    ////////////////////////////////////////////////////////////////////////
    // Il est possible de créer d'autres fonctions ici

    public function __toString() {  // Pour faciliter l'affichage des autres informations d'une entité

        return $this->numero_facture. " ";     
    } 

}
