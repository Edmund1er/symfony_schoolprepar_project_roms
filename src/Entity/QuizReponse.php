<?php

namespace App\Entity;

use App\Repository\QuizReponseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizReponseRepository::class)]
#[ORM\Table(name: 'quiz_reponses')]
class QuizReponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QuizQuestion $question = null;

    #[ORM\Column(length: 255)]
    private ?string $texte = null;

    #[ORM\Column]
    private ?bool $estCorrecte = false;

    #[ORM\Column(nullable: true)]
    private ?int $points = null;

    public function getId(): ?int { return $this->id; }
    public function getQuestion(): ?QuizQuestion { return $this->question; }
    public function setQuestion(?QuizQuestion $question): static { $this->question = $question; return $this; }
    public function getTexte(): ?string { return $this->texte; }
    public function setTexte(string $texte): static { $this->texte = $texte; return $this; }
    public function isEstCorrecte(): ?bool { return $this->estCorrecte; }
    public function setEstCorrecte(bool $estCorrecte): static { $this->estCorrecte = $estCorrecte; return $this; }
    public function getPoints(): ?int { return $this->points; }
    public function setPoints(?int $points): static { $this->points = $points; return $this; }
}