<?php

namespace App\Entity;

use App\Repository\ImagePRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagePRepository::class)]
class ImageP
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $namep = null;

    #[ORM\ManyToOne(inversedBy: 'imagePs')]
    private ?Produit $produits = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamep(): ?string
    {
        return $this->namep;
    }

    public function setNamep(string $namep): static
    {
        $this->namep = $namep;

        return $this;
    }

    public function getProduits(): ?Produit
    {
        return $this->produits;
    }

    public function setProduits(?Produit $produits): static
    {
        $this->produits = $produits;

        return $this;
    }
}
