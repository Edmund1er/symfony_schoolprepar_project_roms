<?php

namespace App\Entity;

use App\Repository\ForumCategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ForumCategorieRepository::class)]
#[ORM\Table(name: 'forum_categories')]
class ForumCategorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $ordre = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: ForumSujet::class)]
    private Collection $sujets;

    public function __construct()
    {
        $this->sujets = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }
    public function getOrdre(): ?int { return $this->ordre; }
    public function setOrdre(?int $ordre): static { $this->ordre = $ordre; return $this; }
    public function getSujets(): Collection { return $this->sujets; }
    public function addSujet(ForumSujet $sujet): static { if (!$this->sujets->contains($sujet)) { $this->sujets->add($sujet); $sujet->setCategorie($this); } return $this; }
    public function removeSujet(ForumSujet $sujet): static { if ($this->sujets->removeElement($sujet) && $sujet->getCategorie() === $this) { $sujet->setCategorie(null); } return $this; }
}