<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prix = null;

    /**
     * @var Collection<int, ImageP>
     */
    #[ORM\OneToMany(targetEntity: ImageP::class, mappedBy: 'produits', cascade: ['persist', 'remove'])]
    private Collection $imagePs;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Categorie $categorie = null;

    public function __construct()
    {
        $this->imagePs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, ImageP>
     */
    public function getImagePs(): Collection
    {
        return $this->imagePs;
    }

    public function addImageP(ImageP $imageP): static
    {
        if (!$this->imagePs->contains($imageP)) {
            $this->imagePs->add($imageP);
            $imageP->setProduits($this);
        }

        return $this;
    }

    public function removeImageP(ImageP $imageP): static
    {
        if ($this->imagePs->removeElement($imageP)) {
            // set the owning side to null (unless already changed)
            if ($imageP->getProduits() === $this) {
                $imageP->setProduits(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }
}
