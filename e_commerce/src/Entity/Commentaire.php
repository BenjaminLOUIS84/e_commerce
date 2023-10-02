<?php

namespace App\Entity;

use App\Entity\Newsletters\Newsletters;
use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $commentaire = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_com = null;

    #[ORM\ManyToOne(inversedBy: 'commentaire')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Newsletters::class, mappedBy: 'commentaire')]
    private Collection $newsletters;

    public function __construct()
    {
        $this->date_com = new \DateTimeImmutable();    // Pour injecter la date automatiquement
        $this->newsletters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getDateCom(): ?\DateTimeInterface
    {
        return $this->date_com;
    }

    public function setDateCom(\DateTimeInterface $date_com): static
    {
        $this->date_com = $date_com;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    ////////////////////////////////////////////////////////////////////////
    // Il est possible de créer d'autres fonctions ici

    public function __toString() {  // Pour faciliter l'affichage des autres informations d'une entité

        return $this->commentaire. " ";     
    }

    /**
     * @return Collection<int, Newsletters>
     */
    public function getNewsletters(): Collection
    {
        return $this->newsletters;
    }

    public function addNewsletter(Newsletters $newsletter): static
    {
        if (!$this->newsletters->contains($newsletter)) {
            $this->newsletters->add($newsletter);
            $newsletter->addCommentaire($this);
        }

        return $this;
    }

    public function removeNewsletter(Newsletters $newsletter): static
    {
        if ($this->newsletters->removeElement($newsletter)) {
            $newsletter->removeCommentaire($this);
        }

        return $this;
    }
}
