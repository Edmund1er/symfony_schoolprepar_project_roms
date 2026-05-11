<?php

namespace App\Entity;

use App\Repository\ParcoursEtapeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParcoursEtapeRepository::class)]
#[ORM\Table(name: 'parcours_etapes')]
class ParcoursEtape
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'etapes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Parcours $parcours = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\Column(length: 30)]
    private ?string $type = null; // quiz, rdv, formation, article

    #[ORM\Column]
    private ?int $referenceId = null; // ID de l'entité associée

    #[ORM\Column]
    private ?bool $estComplete = false;

    public function getId(): ?int { return $this->id; }
    public function getParcours(): ?Parcours { return $this->parcours; }
    public function setParcours(?Parcours $parcours): static { $this->parcours = $parcours; return $this; }
    public function getOrdre(): ?int { return $this->ordre; }
    public function setOrdre(int $ordre): static { $this->ordre = $ordre; return $this; }
    public function getType(): ?string { return $this->type; }
    public function setType(string $type): static { $this->type = $type; return $this; }
    public function getReferenceId(): ?int { return $this->referenceId; }
    public function setReferenceId(int $referenceId): static { $this->referenceId = $referenceId; return $this; }
    public function isEstComplete(): ?bool { return $this->estComplete; }
    public function setEstComplete(bool $estComplete): static { $this->estComplete = $estComplete; return $this; }
}
