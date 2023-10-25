<?php

namespace App\Entity\Newsletters;

use Cocur\Slugify\Slugify;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\Newsletters\CategoriesRepository;
// use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;
    
    ///////////////////////////////////////////////////////////////////////
    //Créer un attribut Slug pour améliorer l'URL (Protection et Référencement)
    // #[ORM\Column(length: 255, unique: true)]
    // #[Assert\NotBlank()]
    // private ?string $slug = null;
    ///////////////////////////////////////////////////////////////////////

    #[ORM\ManyToMany(targetEntity: Users::class, mappedBy: 'categories')]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'categories', targetEntity: Newsletters::class, orphanRemoval: true)]
    private Collection $newsletters;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->newsletters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    ///////////////////////////////////////////////////////////////////////

    public function getSlug(): ?string
    {
        return (new Slugify())->slugify($this->name);
    }

    ///////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////
    // Importer le getteur et le setteur
    // public function getSlug(): ?string
    // {
    //     return $this->slug;
    // }

    // public function setSlug(string $slug): static
    // {
    //     $this->slug = $slug;

    //     return $this;
    // }
    ///////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////
    // Il est possible de créer d'autres fonctions ici

    public function __toString() {  // Pour faciliter l'affichage des autres informations d'une entité

        return $this->name. " ";     
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addCategory($this);
        }

        return $this;
    }

    public function removeUser(Users $user): static
    {
        if($this->users->removeElement($user)){
            $user->removeCategory($this);
        };

        return $this;
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
            $newsletter->setCategories($this);
        }

        return $this;
    }

    public function removeNewsletter(Newsletters $newsletter): static
    {
        if ($this->newsletters->removeElement($newsletter)) {
            // set the owning side to null (unless already changed)
            if ($newsletter->getCategories() === $this) {
                $newsletter->setCategories(null);
            }
        }

        return $this;
    }
}
