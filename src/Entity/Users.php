<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_PSEUDO', fields: ['pseudo'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column]
    private ?bool $isValid = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToOne(mappedBy: 'streamersPresentation', cascade: ['persist'])]
    private ?Presentations $streamersPresentation = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: SocialsNetwork::class)]
    private Collection $socialsNetworks;

    #[ORM\OneToMany(targetEntity: Articles::class, mappedBy: 'users')]
    private Collection $blogueursArticle;

    #[ORM\OneToMany(targetEntity: Outsiders::class, mappedBy: 'user')]
    private Collection $outsiders;

    #[ORM\OneToMany(targetEntity: Articles::class, mappedBy: 'blogueurArticles')]
    private Collection $articles;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\ManyToMany(targetEntity: Evenements::class, mappedBy: 'participants')]
    private Collection $evenements;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Cagnotte::class)]
    private Collection $cagnottes;

    private $resetToken;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Cagnotte $cagnotteStreamers = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $pronoms = null;

    public function __construct()
    {
        $this->socialsNetworks = new ArrayCollection();
        $this->blogueursArticle = new ArrayCollection();
        $this->outsiders = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->evenements = new ArrayCollection();
        $this->cagnottes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return array<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;
        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->isValid;
    }

    public function setValid(bool $isValid): static
    {
        $this->isValid = $isValid;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getStreamersPresentation(): ?Presentations
    {
        return $this->streamersPresentation;
    }

    public function setStreamersPresentation(?Presentations $streamersPresentation): static
    {
        // Set the owning side of the relation if necessary
        if ($streamersPresentation !== null && $streamersPresentation->getStreamersPresentation() !== $this) {
            $streamersPresentation->setStreamersPresentation($this);
        }

        $this->streamersPresentation = $streamersPresentation;

        return $this;
    }
    /**
     * @return Collection<int, SocialsNetwork>
     */
    public function getSocialsNetworks(): Collection
    {
        return $this->socialsNetworks;
    }

    public function addSocialsNetwork(SocialsNetwork $socialsNetwork): self
    {
        if (!$this->socialsNetworks->contains($socialsNetwork)) {
            $this->socialsNetworks->add($socialsNetwork);
            $socialsNetwork->setUser($this);
        }

        return $this;
    }

    public function removeSocialsNetwork(SocialsNetwork $socialsNetwork): self
    {
        if ($this->socialsNetworks->removeElement($socialsNetwork)) {
            // set the owning side to null (unless already changed)
            if ($socialsNetwork->getUser() === $this) {
                $socialsNetwork->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Articles>
     */
    public function getBlogueursArticle(): Collection
    {
        return $this->blogueursArticle;
    }

    public function addBlogueursArticle(Articles $blogueursArticle): static
    {
        if (!$this->blogueursArticle->contains($blogueursArticle)) {
            $this->blogueursArticle->add($blogueursArticle);
            $blogueursArticle->setUsers($this);
        }
        return $this;
    }

    public function removeBlogueursArticle(Articles $blogueursArticle): static
    {
        if ($this->blogueursArticle->removeElement($blogueursArticle)) {
            // set the owning side to null (unless already changed)
            if ($blogueursArticle->getUsers() === $this) {
                $blogueursArticle->setUsers(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Outsiders>
     */
    public function getOutsiders(): Collection
    {
        return $this->outsiders;
    }

    public function addOutsider(Outsiders $outsider): static
    {
        if (!$this->outsiders->contains($outsider)) {
            $this->outsiders->add($outsider);
            $outsider->setUser($this);
        }
        return $this;
    }

    public function removeOutsider(Outsiders $outsider): static
    {
        if ($this->outsiders->removeElement($outsider)) {
            // set the owning side to null (unless already changed)
            if ($outsider->getUser() === $this) {
                $outsider->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Articles>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setBlogueurArticles($this);
        }
        return $this;
    }

    public function removeArticle(Articles $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getBlogueurArticles() === $this) {
                $article->setBlogueurArticles(null);
            }
        }
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Evenements>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenements $evenement): static
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->addParticipant($this);  // Mise à jour de l'autre côté de la relation
        }

        return $this;
    }

    public function removeEvenement(Evenements $evenement): static
    {
        if ($this->evenements->removeElement($evenement)) {
            $evenement->removeParticipant($this);  // Mise à jour de l'autre côté de la relation
        }

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getCagnotteStreamers(): ?Cagnotte
    {
        return $this->cagnotteStreamers;
    }

    public function setCagnotteStreamers(?Cagnotte $cagnotteStreamers): static
    {
        $this->cagnotteStreamers = $cagnotteStreamers;

        return $this;
    }

    /**
     * @return Collection<int, Cagnotte>
     */
    public function getCagnottes(): Collection
    {
        return $this->cagnottes;
    }

    public function addCagnotte(Cagnotte $cagnotte): static
    {
        if (!$this->cagnottes->contains($cagnotte)) {
            $this->cagnottes->add($cagnotte);
            $cagnotte->setUser($this); // Mise à jour de l'autre côté de la relation
        }

        return $this;
    }

    public function removeCagnotte(Cagnotte $cagnotte): static
    {
        if ($this->cagnottes->removeElement($cagnotte)) {
            // set the owning side to null (unless already changed)
            if ($cagnotte->getUser() === $this) {
                $cagnotte->setUser(null);
            }
        }

        return $this;
    }

    public function getPronoms(): ?string
    {
        return $this->pronoms;
    }

    public function setPronoms(?string $pronoms): static
    {
        $this->pronoms = $pronoms;

        return $this;
    }
}
