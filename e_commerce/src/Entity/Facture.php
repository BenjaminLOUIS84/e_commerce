<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use App\Entity\Facture;
use App\Entity\Commande;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FactureRepository;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private ?string $numero_facture = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_facture = null;

    #[ORM\ManyToOne(inversedBy: 'Facture')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $commande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->date_facture = new \DateTimeImmutable();    // Pour injecter la date automatiquement
    }

    public function getNumeroFacture(): ?string
    {
        return $this->numero_facture;
    }

    public function setNumeroFacture(string $numero_facture): static
    {
        $this->numero_facture = $numero_facture;

        return $this;
    }

    public function getSlug(): ?string
    {
        return (new Slugify())->slugify($this->numero_facture);
    }

    public function getDateFacture(): ?\DateTimeImmutable
    {
        return $this->date_facture;
    }

    public function setDateFacture(\DateTimeImmutable $date_facture): static
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
