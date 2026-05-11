<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
#[ORM\Table(name: 'rendez_vous')]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mentorat $mentorat = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateHeure = null;

    #[ORM\Column(nullable: true)]
    private ?int $duree = 30; // minutes

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lienVisio = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = 'programme'; // programme, confirme, annule, termine

    public function getId(): ?int { return $this->id; }
    public function getMentorat(): ?Mentorat { return $this->mentorat; }
    public function setMentorat(?Mentorat $mentorat): static { $this->mentorat = $mentorat; return $this; }
    public function getDateHeure(): ?\DateTimeInterface { return $this->dateHeure; }
    public function setDateHeure(\DateTimeInterface $dateHeure): static { $this->dateHeure = $dateHeure; return $this; }
    public function getDuree(): ?int { return $this->duree; }
    public function setDuree(?int $duree): static { $this->duree = $duree; return $this; }
    public function getLienVisio(): ?string { return $this->lienVisio; }
    public function setLienVisio(?string $lienVisio): static { $this->lienVisio = $lienVisio; return $this; }
    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(string $statut): static { $this->statut = $statut; return $this; }
}