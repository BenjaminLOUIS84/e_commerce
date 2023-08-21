<?php

namespace App\Entity;

use App\Entity\Format;
use App\Entity\FormatLivre;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormatRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File; // Pour permettre le chargement et l'affichage des images

#[ORM\Entity(repositoryClass: FormatRepository::class)]
class Format
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\OneToMany(mappedBy: 'format', targetEntity: FormatLivre::class)]
    private Collection $formatLivres;

    public function __construct()
    {
        $this->formatLivres = new ArrayCollection();
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

    ////////////////////////////////////////////////////////////////////////
    // Il est possible de créer d'autres fonctions ici

    public function __toString() {          // Pour faciliter l'affichage des autres informations d'une entité

        return $this->type. " ";            // L'élément affiché de la liste des formats est seulement le type
    }                                       // Permet d'afficher le type dans le détail d'un fomrat ET AUSSI dans le détail des autres entités

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, FormatLivre>
     */
    public function getFormatLivres(): Collection
    {
        return $this->formatLivres;
    }

    public function addFormatLivre(FormatLivre $formatLivre): static
    {
        if (!$this->formatLivres->contains($formatLivre)) {
            $this->formatLivres->add($formatLivre);
            $formatLivre->setFormat($this);
        }

        return $this;
    }

    public function removeFormatLivre(FormatLivre $formatLivre): static
    {
        if ($this->formatLivres->removeElement($formatLivre)) {
            // set the owning side to null (unless already changed)
            if ($formatLivre->getFormat() === $this) {
                $formatLivre->setFormat(null);
            }
        }

        return $this;
    }

   

}
