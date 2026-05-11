<?php

namespace App\Entity;

use App\Repository\QuizQuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizQuestionRepository::class)]
#[ORM\Table(name: 'quiz_questions')]
class QuizQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quiz $quiz = null;

    #[ORM\Column(type: 'text')]
    private ?string $texte = null;

    #[ORM\Column(nullable: true)]
    private ?int $ordre = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: QuizReponse::class, cascade: ['persist', 'remove'])]
    private Collection $reponses;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getQuiz(): ?Quiz { return $this->quiz; }
    public function setQuiz(?Quiz $quiz): static { $this->quiz = $quiz; return $this; }
    public function getTexte(): ?string { return $this->texte; }
    public function setTexte(string $texte): static { $this->texte = $texte; return $this; }
    public function getOrdre(): ?int { return $this->ordre; }
    public function setOrdre(?int $ordre): static { $this->ordre = $ordre; return $this; }
    public function getReponses(): Collection { return $this->reponses; }
}