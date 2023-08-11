<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormatRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: FormatRepository::class)]
class Format
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\ManyToMany(targetEntity: Livre::class, inversedBy: 'formats')]
    private Collection $livre;

    public function __construct()
    {
        $this->livre = new ArrayCollection();
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

    /**
     * @return Collection<int, Livre>
     */
    public function getLivre(): Collection
    {
        return $this->livre;
    }

    public function addLivre(Livre $livre): static // Pour ajouter un format
    {
        if (!$this->livre->contains($livre)) {
            $this->livre->add($livre);
            $livre->setFormat($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): static // Pour supprimer un livre
    {
        if ($this->livre->removeElement($livre)) {
            if ($livre->getFormat() === $this) {
                $livre->setFormat(null);
            }
            return $this;
        }
        
    }
    ////////////////////////////////////////////////////////////////////////
    // Il est possible de créer d'autres fonctions ici

    public function __toString() {          // Pour faciliter l'affichage des autres informations d'une entité

        return $this->type. " ";            // L'élément affiché de la liste des formats est seulement le type
    }                                       // Permet d'afficher le type dans le détail d'un fomrat ET AUSSI dans le détail des autres entités

}
