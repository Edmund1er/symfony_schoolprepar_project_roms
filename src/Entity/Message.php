<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Table(name: 'messages')]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conversation $conversation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $expediteur = null;

    #[ORM\Column(type: 'text')]
    private ?string $contenu = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateEnvoi = null;

    #[ORM\Column]
    private ?bool $estLu = false;

    public function __construct()
    {
        $this->dateEnvoi = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function getConversation(): ?Conversation { return $this->conversation; }
    public function setConversation(?Conversation $conversation): static { $this->conversation = $conversation; return $this; }
    public function getExpediteur(): ?User { return $this->expediteur; }
    public function setExpediteur(?User $expediteur): static { $this->expediteur = $expediteur; return $this; }
    public function getContenu(): ?string { return $this->contenu; }
    public function setContenu(string $contenu): static { $this->contenu = $contenu; return $this; }
    public function getDateEnvoi(): ?\DateTimeInterface { return $this->dateEnvoi; }
    public function setDateEnvoi(\DateTimeInterface $dateEnvoi): static { $this->dateEnvoi = $dateEnvoi; return $this; }
    public function isEstLu(): ?bool { return $this->estLu; }
    public function setEstLu(bool $estLu): static { $this->estLu = $estLu; return $this; }
}
