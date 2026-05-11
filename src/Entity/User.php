<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "app_user")]  
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private array $role = [];

    #[ORM\ManyToOne(inversedBy: 'utilisateurs')]
    private ?Filiere $filiere = null;

    #[ORM\ManyToMany(targetEntity: Evenement::class, inversedBy: 'participants')]
    private Collection $evenements;

    // ========== CHAMPS POUR LE MENTORAT ==========
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $specialite = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $biographie = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $competences = null;

    #[ORM\Column(nullable: true)]
    private ?int $experience = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $noteMoyenne = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbAvis = null;

    // ========== RELATIONS POUR LE MENTORAT ==========
    #[ORM\OneToMany(mappedBy: 'eleve', targetEntity: Mentorat::class)]
    private Collection $demandesMentorat;

    #[ORM\OneToMany(mappedBy: 'mentor', targetEntity: Mentorat::class)]
    private Collection $mentorats;

    #[ORM\OneToMany(mappedBy: 'mentor', targetEntity: Disponibilite::class)]
    private Collection $disponibilites;

    // ========== RELATIONS POUR LE FORUM ==========
    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: ForumSujet::class)]
    private Collection $forumSujets;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: ForumMessage::class)]
    private Collection $forumMessages;

    // ========== RELATIONS POUR LA MESSAGERIE ==========
    #[ORM\OneToMany(mappedBy: 'user1', targetEntity: Conversation::class)]
    private Collection $conversations1;

    #[ORM\OneToMany(mappedBy: 'user2', targetEntity: Conversation::class)]
    private Collection $conversations2;

    #[ORM\OneToMany(mappedBy: 'expediteur', targetEntity: Message::class)]
    private Collection $messagesEnvoyes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notification::class)]
    private Collection $notifications;

    // ========== RELATIONS POUR LES QUIZ ==========
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserQuizResult::class)]
    private Collection $quizResultats;

    // ========== RELATIONS POUR LES PARCOURS ==========
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Parcours::class)]
    private Collection $parcours;

    // ========== RELATIONS POUR LES RECOMMANDATIONS ==========
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Recommandation::class)]
    private Collection $recommandations;

    #[Assert\NotBlank(message: 'Le mot de passe est obligatoire')]
    #[Assert\Length(min: 6, minMessage: 'Le mot de passe doit contenir au moins 6 caracteres')]
    private ?string $plainPassword = null;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
        $this->demandesMentorat = new ArrayCollection();
        $this->mentorats = new ArrayCollection();
        $this->disponibilites = new ArrayCollection();
        $this->forumSujets = new ArrayCollection();
        $this->forumMessages = new ArrayCollection();
        $this->conversations1 = new ArrayCollection();
        $this->conversations2 = new ArrayCollection();
        $this->messagesEnvoyes = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->quizResultats = new ArrayCollection();
        $this->parcours = new ArrayCollection();
        $this->recommandations = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }
    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(?string $prenom): static { $this->prenom = $prenom; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }
    public function getPassword(): ?string { return $this->password; }
    public function setPassword(string $password): static { $this->password = $password; return $this; }
    public function getRole(): array { return $this->role; }
    public function setRole(array $role): static { $this->role = $role; return $this; }
    public function getFiliere(): ?Filiere { return $this->filiere; }
    public function setFiliere(?Filiere $filiere): static { $this->filiere = $filiere; return $this; }
    
    public function getEvenements(): Collection { return $this->evenements; }
    public function addEvenement(Evenement $evenement): static
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->addParticipant($this);
        }
        return $this;
    }
    public function removeEvenement(Evenement $evenement): static
    {
        if ($this->evenements->removeElement($evenement)) {
            $evenement->removeParticipant($this);
        }
        return $this;
    }

    // Mentorat getters/setters
    public function getSpecialite(): ?string { return $this->specialite; }
    public function setSpecialite(?string $specialite): static { $this->specialite = $specialite; return $this; }
    public function getBiographie(): ?string { return $this->biographie; }
    public function setBiographie(?string $biographie): static { $this->biographie = $biographie; return $this; }
    public function getCompetences(): ?string { return $this->competences; }
    public function setCompetences(?string $competences): static { $this->competences = $competences; return $this; }
    public function getExperience(): ?int { return $this->experience; }
    public function setExperience(?int $experience): static { $this->experience = $experience; return $this; }
    public function getNoteMoyenne(): ?float { return $this->noteMoyenne; }
    public function setNoteMoyenne(?float $noteMoyenne): static { $this->noteMoyenne = $noteMoyenne; return $this; }
    public function getNbAvis(): ?int { return $this->nbAvis; }
    public function setNbAvis(?int $nbAvis): static { $this->nbAvis = $nbAvis; return $this; }

    // Collections getters
    public function getDemandesMentorat(): Collection { return $this->demandesMentorat; }
    public function getMentorats(): Collection { return $this->mentorats; }
    public function getDisponibilites(): Collection { return $this->disponibilites; }
    public function getForumSujets(): Collection { return $this->forumSujets; }
    public function getForumMessages(): Collection { return $this->forumMessages; }
    public function getConversations1(): Collection { return $this->conversations1; }
    public function getConversations2(): Collection { return $this->conversations2; }
    public function getMessagesEnvoyes(): Collection { return $this->messagesEnvoyes; }
    public function getNotifications(): Collection { return $this->notifications; }
    public function getQuizResultats(): Collection { return $this->quizResultats; }
    public function getParcours(): Collection { return $this->parcours; }
    public function getRecommandations(): Collection { return $this->recommandations; }

    // Symfony security methods
    public function getUserIdentifier(): string { return $this->email; }
    public function eraseCredentials(): void { $this->plainPassword = null; }
    public function getRoles(): array
    {
        $roles = $this->role;
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }
        return $roles;
    }
    public function getPlainPassword(): ?string { return $this->plainPassword; }
    public function setPlainPassword(?string $plainPassword): static { $this->plainPassword = $plainPassword; return $this; }
}