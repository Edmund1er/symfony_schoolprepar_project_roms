<?php

namespace App\Entity;

use App\Repository\DisponibiliteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DisponibiliteRepository::class)]
#[ORM\Table(name: 'disponibilites')]
class Disponibilite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $mentor = null;  // ← Changé de Mentor à User

    #[ORM\Column]
    private ?int $jourSemaine = null;

    #[ORM\Column(type: 'time')]
    private ?\DateTimeInterface $heureDebut = null;

    #[ORM\Column(type: 'time')]
    private ?\DateTimeInterface $heureFin = null;

    #[ORM\Column]
    private ?bool $estDisponible = true;

    // Getters et Setters
    public function getId(): ?int { return $this->id; }
    public function getMentor(): ?User { return $this->mentor; }
    public function setMentor(?User $mentor): static { $this->mentor = $mentor; return $this; }
    public function getJourSemaine(): ?int { return $this->jourSemaine; }
    public function setJourSemaine(int $jourSemaine): static { $this->jourSemaine = $jourSemaine; return $this; }
    public function getHeureDebut(): ?\DateTimeInterface { return $this->heureDebut; }
    public function setHeureDebut(\DateTimeInterface $heureDebut): static { $this->heureDebut = $heureDebut; return $this; }
    public function getHeureFin(): ?\DateTimeInterface { return $this->heureFin; }
    public function setHeureFin(\DateTimeInterface $heureFin): static { $this->heureFin = $heureFin; return $this; }
    public function isEstDisponible(): ?bool { return $this->estDisponible; }
    public function setEstDisponible(bool $estDisponible): static { $this->estDisponible = $estDisponible; return $this; }
}