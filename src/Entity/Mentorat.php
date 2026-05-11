<?php

namespace App\Entity;

use App\Repository\MentoratRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MentoratRepository::class)]
#[ORM\Table(name: 'mentorats')]
class Mentorat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $eleve = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $mentor = null;  // ← Changé de Mentor à User

    #[ORM\Column(length: 20)]
    private ?string $statut = 'en_attente';

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $demandeLe = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $reponseLe = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $message = null;

    public function __construct()
    {
        $this->demandeLe = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function getEleve(): ?User { return $this->eleve; }
    public function setEleve(?User $eleve): static { $this->eleve = $eleve; return $this; }
    public function getMentor(): ?User { return $this->mentor; }
    public function setMentor(?User $mentor): static { $this->mentor = $mentor; return $this; }
    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(string $statut): static { $this->statut = $statut; return $this; }
    public function getDemandeLe(): ?\DateTimeInterface { return $this->demandeLe; }
    public function setDemandeLe(\DateTimeInterface $demandeLe): static { $this->demandeLe = $demandeLe; return $this; }
    public function getReponseLe(): ?\DateTimeInterface { return $this->reponseLe; }
    public function setReponseLe(?\DateTimeInterface $reponseLe): static { $this->reponseLe = $reponseLe; return $this; }
    public function getMessage(): ?string { return $this->message; }
    public function setMessage(?string $message): static { $this->message = $message; return $this; }
}