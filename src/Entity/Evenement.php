<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $categorie = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $replayUrl = null;

    #[ORM\Column(nullable: true)]
    private ?int $duree = null;

    #[ORM\ManyToOne(targetEntity: Filiere::class)]
    private ?Filiere $filiere = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'evenements')]
    private Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }
    public function getDate(): ?\DateTime { return $this->date; }
    public function setDate(\DateTime $date): static { $this->date = $date; return $this; }
    public function getLieu(): ?string { return $this->lieu; }
    public function setLieu(?string $lieu): static { $this->lieu = $lieu; return $this; }
    public function getCategorie(): ?string { return $this->categorie; }
    public function setCategorie(?string $categorie): static { $this->categorie = $categorie; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }
    public function getReplayUrl(): ?string { return $this->replayUrl; }
    public function setReplayUrl(?string $replayUrl): static { $this->replayUrl = $replayUrl; return $this; }
    public function getDuree(): ?int { return $this->duree; }
    public function setDuree(?int $duree): static { $this->duree = $duree; return $this; }
    public function getFiliere(): ?Filiere { return $this->filiere; }
    public function setFiliere(?Filiere $filiere): static { $this->filiere = $filiere; return $this; }
    public function getParticipants(): Collection { return $this->participants; }
    public function addParticipant(User $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }
        return $this;
    }
    public function removeParticipant(User $participant): static
    {
        $this->participants->removeElement($participant);
        return $this;
    }
}