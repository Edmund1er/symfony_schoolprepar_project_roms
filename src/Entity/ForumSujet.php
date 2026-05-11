<?php

namespace App\Entity;

use App\Repository\ForumSujetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ForumSujetRepository::class)]
#[ORM\Table(name: 'forum_sujets')]
class ForumSujet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $contenu = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $auteur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ForumCategorie $categorie = null;

    #[ORM\Column]
    private ?bool $isResolu = false;

    #[ORM\Column]
    private ?bool $isEpingle = false;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateDernierMessage = null;

    #[ORM\OneToMany(mappedBy: 'sujet', targetEntity: ForumMessage::class, cascade: ['remove'])]
    private Collection $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->dateCreation = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }
    public function getContenu(): ?string { return $this->contenu; }
    public function setContenu(?string $contenu): static { $this->contenu = $contenu; return $this; }
    public function getAuteur(): ?User { return $this->auteur; }
    public function setAuteur(?User $auteur): static { $this->auteur = $auteur; return $this; }
    public function getCategorie(): ?ForumCategorie { return $this->categorie; }
    public function setCategorie(?ForumCategorie $categorie): static { $this->categorie = $categorie; return $this; }
    public function isIsResolu(): ?bool { return $this->isResolu; }
    public function setIsResolu(bool $isResolu): static { $this->isResolu = $isResolu; return $this; }
    public function isIsEpingle(): ?bool { return $this->isEpingle; }
    public function setIsEpingle(bool $isEpingle): static { $this->isEpingle = $isEpingle; return $this; }
    public function getDateCreation(): ?\DateTimeInterface { return $this->dateCreation; }
    public function setDateCreation(\DateTimeInterface $dateCreation): static { $this->dateCreation = $dateCreation; return $this; }
    public function getDateDernierMessage(): ?\DateTimeInterface { return $this->dateDernierMessage; }
    public function setDateDernierMessage(?\DateTimeInterface $dateDernierMessage): static { $this->dateDernierMessage = $dateDernierMessage; return $this; }
    public function getMessages(): Collection { return $this->messages; }
    public function addMessage(ForumMessage $message): static { if (!$this->messages->contains($message)) { $this->messages->add($message); $message->setSujet($this); } return $this; }
    public function removeMessage(ForumMessage $message): static { if ($this->messages->removeElement($message) && $message->getSujet() === $this) { $message->setSujet(null); } return $this; }
}