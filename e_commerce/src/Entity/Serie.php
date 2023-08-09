<?php

namespace App\Entity;

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
}
