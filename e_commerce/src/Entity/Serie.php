<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use App\Repository\SerieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SerieRepository::class)]
class Serie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $intitule = null;

    #[ORM\OneToMany(mappedBy: 'serie', targetEntity: Livre::class)]
    private Collection $Livre;

    public function __construct()
    {
        $this->Livre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getSlug(): ?string
    {
        return (new Slugify())->slugify($this->intitule);
    }

    /**
     * @return Collection<int, Livre>
     */
    public function getLivre(): Collection
    {
        return $this->Livre;
    }

    public function addLivre(Livre $livre): static
    {
        if (!$this->Livre->contains($livre)) {
            $this->Livre->add($livre);
            $livre->setSerie($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): static
    {
        if ($this->Livre->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getSerie() === $this) {
                $livre->setSerie(null);
            }
        }

        return $this;
    }

    ////////////////////////////////////////////////////////////////////////
    // Il est possible de créer d'autres fonctions ici

    public function __toString() {          // Pour faciliter l'affichage des autres informations d'une entité

        return $this->intitule. " ";        // L'élément affiché de la liste des collections est seulement l'intitule
    }                                       // Permet d'afficher l'intule dans le détail d'une collection ET AUSSI dans le détail des autres entités

}
