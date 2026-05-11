<?php

namespace App\Entity;

use App\Repository\RecommandationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecommandationRepository::class)]
#[ORM\Table(name: 'recommandations')]
class Recommandation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 30)]
    private ?string $type = null; // filiere, etablissement, mentor, evenement

    #[ORM\Column]
    private ?int $referenceId = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'float')]
    private ?float $score = null;

    #[ORM\Column]
    private ?bool $estVue = false;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateCreation = null;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static { $this->user = $user; return $this; }
    public function getType(): ?string { return $this->type; }
    public function setType(string $type): static { $this->type = $type; return $this; }
    public function getReferenceId(): ?int { return $this->referenceId; }
    public function setReferenceId(int $referenceId): static { $this->referenceId = $referenceId; return $this; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }
    public function getScore(): ?float { return $this->score; }
    public function setScore(float $score): static { $this->score = $score; return $this; }
    public function isEstVue(): ?bool { return $this->estVue; }
    public function setEstVue(bool $estVue): static { $this->estVue = $estVue; return $this; }
    public function getDateCreation(): ?\DateTimeInterface { return $this->dateCreation; }
    public function setDateCreation(\DateTimeInterface $dateCreation): static { $this->dateCreation = $dateCreation; return $this; }
}