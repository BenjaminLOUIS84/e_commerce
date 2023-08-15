<?php

namespace App\Entity;

use App\Entity\Serie;
use App\Entity\Format;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivreRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $couverture = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_publication = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $resume = null;

    #[ORM\ManyToMany(targetEntity: Commande::class, mappedBy: 'Livre')]
    private Collection $commandes;

    #[ORM\ManyToMany(targetEntity: Format::class, mappedBy: 'Livre')]
    private Collection $formats;

    #[ORM\ManyToOne(inversedBy: 'Livre')]
    private ?Serie $serie = null;

    #[ORM\Column(length: 255)]
    private ?string $tome = null;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->formats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }
    
    public function getCouverture(): ?string
    {
        return $this->couverture;
    }

    public function setCouverture(string $couverture): self
    {
        $this->couverture = $couverture;
        
        return $this;
    }


    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(\DateTimeInterface $date_publication): static
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): static
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->addLivre($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            $commande->removeLivre($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Format>
     */
    public function getFormats(): Collection
    {
        return $this->formats;
    }

    public function addFormat(Format $format): static // Pour permettre l'ajout d'un format
    {
        if (!$this->formats->contains($format)) {
            $this->formats->add($format);
            $format->addLivre($this);
        }

        return $this;
    }

    public function removeFormat(Format $format): static // Pour permettre la suppression d'un format
    {
        if ($this->formats->removeElement($format)) {
            $format->removeLivre($this);
        }

        return $this;
    }

    public function getSerie(): ?Serie
    {
        return $this->serie;
    }

    public function setSerie(?Serie $serie): static
    {
        $this->serie = $serie;

        return $this;
    }

    ////////////////////////////////////////////////////////////////////////
    // Il est possible de créer d'autres fonctions ici

    public function __toString() {              // Pour faciliter l'affichage des autres informations d'une entité

        return $this->titre. " ";               
                                                
    }                                           // Les éléments affichés de la liste des livres sont le titre, la couverture et le tome afin de permettre la modification des livres

    public function getTome(): ?string
    {
        return $this->tome;
    }

    public function setTome(string $tome): static
    {
        $this->tome = $tome;

        return $this;
    }                                       
}
