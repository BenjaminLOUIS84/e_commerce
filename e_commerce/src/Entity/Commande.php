<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Facture;
use App\Entity\Commande;
use App\Entity\CommandeLivre;
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

    // Modifier l'ORM pour rendre le numéro de commande unique
    #[ORM\Column(unique: true)]
    private ?string $numero_commande = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    // #[ORM\JoinColumn(nullable: false)]
    private ?\DateTimeInterface $date_commande = null;

    #[ORM\Column(length: 100, nullable:true)]
    private ?string $nom = null;

    #[ORM\Column(length: 100, nullable:true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 50, nullable:true)]
    private ?string $cp = null;

    #[ORM\Column(length: 100, nullable:true)]
    private ?string $ville = null;


    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Facture::class)]
    private Collection $Facture;

    #[ORM\ManyToOne(inversedBy: 'commande')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
    // Ajouter cascade: 'persist' et orphanRemoval:true dans l'ORM 
    #[ORM\OneToMany(mappedBy: 'commande', cascade: ['persist'], orphanRemoval:true, targetEntity: CommandeLivre::class)]
    private Collection $commandeLivres;
   

    public function __construct()
    {
        $this->Facture = new ArrayCollection();
        $this->commandeLivres = new ArrayCollection();
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroCommande(): ?string
    {
        return $this->numero_commande;
    }

    public function setNumeroCommande(string $numero_commande): static
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

    public function __toString() {  // Pour faciliter l'affichage des autres informations d'une entité

        return $this->nom. " ";     // L'élément affiché de la liste des commande est seulement la date de commande
    }                                       

    // Créer un fonction pour afficher les coordonnées complètes afin de pouvoir la réutiliser et d'alléger le code dans le fichier show.html.twig

    public function getCoordonnees(): string
    {
       return  $this->nom. " " .$this->prenom. " " .$this->adresse. " " .$this->cp. " " .$this->ville; 
    }
    ////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////////////////// 
                                                
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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
            $commandeLivre->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeLivre(CommandeLivre $commandeLivre): static
    {
        if ($this->commandeLivres->removeElement($commandeLivre)) {
            // set the owning side to null (unless already changed)
            if ($commandeLivre->getCommande() === $this) {
                $commandeLivre->setCommande(null);
            }
        }

        return $this;
    }
    
    // Créer un fonction pour calculer le Total de la commande

    public function getTotalCommande(){
        $total = 0;
        foreach ($this->getCommandeLivres() as $commandeLivres){
          $total += $commandeLivres->getSousTotal();
        }
        return $total;
      }
    ////////////////////////////////////////////////////////////////////////
    
}
