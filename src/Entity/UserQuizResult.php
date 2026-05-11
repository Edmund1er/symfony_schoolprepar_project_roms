<?php

namespace App\Entity;

use App\Repository\UserQuizResultRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserQuizResultRepository::class)]
#[ORM\Table(name: 'user_quiz_results')]
class UserQuizResult
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quiz $quiz = null;

    #[ORM\Column(nullable: true)]
    private ?int $score = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalPoints = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateCompletion = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $resultat = null;

    public function __construct()
    {
        $this->dateCompletion = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static { $this->user = $user; return $this; }
    public function getQuiz(): ?Quiz { return $this->quiz; }
    public function setQuiz(?Quiz $quiz): static { $this->quiz = $quiz; return $this; }
    public function getScore(): ?int { return $this->score; }
    public function setScore(?int $score): static { $this->score = $score; return $this; }
    public function getTotalPoints(): ?int { return $this->totalPoints; }
    public function setTotalPoints(?int $totalPoints): static { $this->totalPoints = $totalPoints; return $this; }
    public function getDateCompletion(): ?\DateTimeInterface { return $this->dateCompletion; }
    public function setDateCompletion(\DateTimeInterface $dateCompletion): static { $this->dateCompletion = $dateCompletion; return $this; }
    public function getResultat(): ?array { return $this->resultat; }
    public function setResultat(?array $resultat): static { $this->resultat = $resultat; return $this; }
}