<?php

namespace App\Entity;

use App\Repository\FormatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
    private Collection $Livre;

    #[ORM\OneToMany(mappedBy: 'format', targetEntity: FormatLivre::class)]
    private Collection $FormatLivre;

    public function __construct()
    {
        $this->Livre = new ArrayCollection();
        $this->FormatLivre = new ArrayCollection();
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
        return $this->Livre;
    }

    public function addLivre(Livre $livre): static
    {
        if (!$this->Livre->contains($livre)) {
            $this->Livre->add($livre);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): static
    {
        $this->Livre->removeElement($livre);

        return $this;
    }

    /**
     * @return Collection<int, FormatLivre>
     */
    public function getFormatLivre(): Collection
    {
        return $this->FormatLivre;
    }

    public function addFormatLivre(FormatLivre $formatLivre): static
    {
        if (!$this->FormatLivre->contains($formatLivre)) {
            $this->FormatLivre->add($formatLivre);
            $formatLivre->setFormat($this);
        }

        return $this;
    }

    public function removeFormatLivre(FormatLivre $formatLivre): static
    {
        if ($this->FormatLivre->removeElement($formatLivre)) {
            // set the owning side to null (unless already changed)
            if ($formatLivre->getFormat() === $this) {
                $formatLivre->setFormat(null);
            }
        }

        return $this;
    }
}
