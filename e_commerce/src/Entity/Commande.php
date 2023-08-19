<?php

namespace App\Entity;

use App\Entity\Livre;
use App\Entity\Facture;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numero_commande = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_commande = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 50)]
    private ?string $cp = null;

    #[ORM\Column(length: 100)]
    private ?string $ville = null;

    #[ORM\ManyToMany(targetEntity: Livre::class, inversedBy: 'commandes')]
    private Collection $Livre;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Facture::class)]
    private Collection $Facture;

   

    public function __construct()
    {
        $this->Livre = new ArrayCollection();
        $this->Facture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroCommande(): ?int
    {
        return $this->numero_commande;
    }

    public function setNumeroCommande(int $numero_commande): static
    {
        $this->numero_commande = $numero_commande;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTimeInterface $date_commande): static
    {
        $this->date_commande = $date_commande;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): static
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

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
     * @return Collection<int, Facture>
     */
    public function getFacture(): Collection
    {
        return $this->Facture;
    }

    public function addFacture(Facture $facture): static
    {
        if (!$this->Facture->contains($facture)) {
            $this->Facture->add($facture);
            $facture->setCommande($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->Facture->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getCommande() === $this) {
                $facture->setCommande(null);
            }
        }

        return $this;
    }

    ////////////////////////////////////////////////////////////////////////
    // Il est possible de créer d'autres fonctions ici

    public function __toString() {          // Pour faciliter l'affichage des autres informations d'une entité

        return $this->nom. " ";   // L'élément affiché de la liste des commande est seulement la date de commande
    }                                       

    
    
}
