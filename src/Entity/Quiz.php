<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
#[ORM\Table(name: 'quizs')]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 30)]
    private ?string $type = 'orientation'; // orientation, personnalite, connaissance

    #[ORM\Column(nullable: true)]
    private ?int $duree = null; // minutes

    #[ORM\Column]
    private ?bool $estActif = true;

    #[ORM\OneToMany(mappedBy: 'quiz', targetEntity: QuizQuestion::class, cascade: ['persist', 'remove'])]
    private Collection $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }
    public function getType(): ?string { return $this->type; }
    public function setType(string $type): static { $this->type = $type; return $this; }
    public function getDuree(): ?int { return $this->duree; }
    public function setDuree(?int $duree): static { $this->duree = $duree; return $this; }
    public function isEstActif(): ?bool { return $this->estActif; }
    public function setEstActif(bool $estActif): static { $this->estActif = $estActif; return $this; }
    public function getQuestions(): Collection { return $this->questions; }
}