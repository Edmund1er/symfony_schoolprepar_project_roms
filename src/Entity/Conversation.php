<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
#[ORM\Table(name: 'conversations')]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user1 = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user2 = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateDernierMessage = null;

    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Message::class, cascade: ['remove'])]
    private Collection $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->dateCreation = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function getUser1(): ?User { return $this->user1; }
    public function setUser1(?User $user1): static { $this->user1 = $user1; return $this; }
    public function getUser2(): ?User { return $this->user2; }
    public function setUser2(?User $user2): static { $this->user2 = $user2; return $this; }
    public function getDateCreation(): ?\DateTimeInterface { return $this->dateCreation; }
    public function setDateCreation(\DateTimeInterface $dateCreation): static { $this->dateCreation = $dateCreation; return $this; }
    public function getDateDernierMessage(): ?\DateTimeInterface { return $this->dateDernierMessage; }
    public function setDateDernierMessage(?\DateTimeInterface $dateDernierMessage): static { $this->dateDernierMessage = $dateDernierMessage; return $this; }
    public function getMessages(): Collection { return $this->messages; }
}