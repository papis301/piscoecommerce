<?php

namespace App\Entity;

use App\Repository\NomBoutiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NomBoutiqueRepository::class)]
class NomBoutique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomboutique = null;

    /**
     * @var Collection<int, ImageB>
     */
    #[ORM\OneToMany(targetEntity: ImageB::class, mappedBy: 'boutiquelogo', cascade: ['persist', 'remove'])]
    private Collection $imageBs;

    public function __construct()
    {
        $this->imageBs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomboutique(): ?string
    {
        return $this->nomboutique;
    }

    public function setNomboutique(string $nomboutique): static
    {
        $this->nomboutique = $nomboutique;

        return $this;
    }

    /**
     * @return Collection<int, ImageB>
     */
    public function getImageBs(): Collection
    {
        return $this->imageBs;
    }

    public function addImageB(ImageB $imageB): static
    {
        if (!$this->imageBs->contains($imageB)) {
            $this->imageBs->add($imageB);
            $imageB->setBoutiquelogo($this);
        }

        return $this;
    }

    public function removeImageB(ImageB $imageB): static
    {
        if ($this->imageBs->removeElement($imageB)) {
            // set the owning side to null (unless already changed)
            if ($imageB->getBoutiquelogo() === $this) {
                $imageB->setBoutiquelogo(null);
            }
        }

        return $this;
    }
}
