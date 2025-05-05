<?php

namespace App\Entity;

use App\Repository\ImageBRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageBRepository::class)]
class ImageB
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nameb = null;

    #[ORM\ManyToOne(inversedBy: 'imageBs')]
    private ?NomBoutique $boutiquelogo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameb(): ?string
    {
        return $this->nameb;
    }

    public function setNameb(string $nameb): static
    {
        $this->nameb = $nameb;

        return $this;
    }

    public function getBoutiquelogo(): ?NomBoutique
    {
        return $this->boutiquelogo;
    }

    public function setBoutiquelogo(?NomBoutique $boutiquelogo): static
    {
        $this->boutiquelogo = $boutiquelogo;

        return $this;
    }
}
