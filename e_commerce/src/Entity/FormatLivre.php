<?php

namespace App\Entity;

use App\Repository\FormatLivreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormatLivreRepository::class)]
class FormatLivre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $prix_unitaire = null;

    #[ORM\ManyToOne(inversedBy: 'FormatLivre')]
    private ?Format $format = null;

    #[ORM\ManyToOne(inversedBy: 'formatLivres')]
    private ?Livre $Livre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->prix_unitaire;
    }

    public function setPrixUnitaire(float $prix_unitaire): static
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
        return $this->Livre;
    }

    public function setLivre(?Livre $Livre): static
    {
        $this->Livre = $Livre;

        return $this;
    }
}
