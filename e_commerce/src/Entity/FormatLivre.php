<?php

namespace App\Entity;

use App\Entity\Livre;
use App\Entity\Format;
use App\Entity\FormatLivre;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormatLivreRepository;

#[ORM\Entity(repositoryClass: FormatLivreRepository::class)]
class FormatLivre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $prix_unitaire = null;

    #[ORM\ManyToOne(inversedBy: 'formatLivres')]
    private ?Format $format = null;

    #[ORM\ManyToOne(inversedBy: 'formatLivres')]
    private ?Livre $livre = null;

    

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFormat(): ?Format
    {
        return $this->format;
    }

    public function setFormat(?Format $format): static
    {
        $this->format = $format;

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
}
