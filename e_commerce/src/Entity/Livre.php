<?php

namespace App\Entity;

use App\Entity\Livre;
use App\Entity\Serie;
use App\Entity\Format;
use App\Entity\FormatLivre;
use App\Entity\CommandeLivre;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivreRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File; // Pour permettre le chargement et l'affichage des images

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


    #[ORM\ManyToOne(inversedBy: 'Livre')]
    private ?Serie $serie = null;

    #[ORM\Column(length: 255)]
    private ?string $tome = null;

    #[ORM\OneToMany(mappedBy: 'livre', targetEntity: CommandeLivre::class)]
    private Collection $commandeLivres;
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
    // Ajouter cascade: 'persist' et orphanRemoval:true dans l'ORM 
    #[ORM\OneToMany(mappedBy: 'livre',  cascade: ['persist'], orphanRemoval:true, targetEntity: FormatLivre::class)]
    private Collection $formatLivres;

    public function __construct()
    {
        $this->commandeLivres = new ArrayCollection();
        $this->formatLivres = new ArrayCollection();
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

    /**
     * @return Collection<int, CommandeLivre>
     */
    public function getCommandeLivres(): Collection
    {
        return $this->commandeLivres;
    }

    public function addCommandeLivre(CommandeLivre $commandeLivre): static
    {
        if (!$this->commandeLivres->contains($commandeLivre)) {
            $this->commandeLivres->add($commandeLivre);
            $commandeLivre->setLivre($this);
        }

        return $this;
    }

    public function removeCommandeLivre(CommandeLivre $commandeLivre): static
    {
        if ($this->commandeLivres->removeElement($commandeLivre)) {
            // set the owning side to null (unless already changed)
            if ($commandeLivre->getLivre() === $this) {
                $commandeLivre->setLivre(null);
            }
        }

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
            $formatLivre->setLivre($this);
        }

        return $this;
    }

    public function removeFormatLivre(FormatLivre $formatLivre): static
    {
        if ($this->formatLivres->removeElement($formatLivre)) {
            // set the owning side to null (unless already changed)
            if ($formatLivre->getLivre() === $this) {
                $formatLivre->setLivre(null);
            }
        }

        return $this;
    }

    //Pour permettre l'affichage du champ serie dans le formulaire de création de livres
    /**
     * Get the value of serie
     */ 
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set the value of serie
     *
     * @return  self
     */ 
    public function setSerie($serie)
    {
        $this->serie = $serie;

        return $this;
    }
}
