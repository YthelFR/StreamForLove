<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\UniqueConstraint(name: 'UNIQ_PSEUDO', fields: ['pseudo'])]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column(type:'json')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column]
    private ?bool $isValid = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Evenements>
     */
    #[ORM\OneToMany(targetEntity: Evenements::class, mappedBy: 'users')]
    private Collection $adminEvenements;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Presentations $streamersPresentation = null;

    /**
     * @var Collection<int, SocialsNetwork>
     */
    #[ORM\OneToMany(targetEntity: SocialsNetwork::class, mappedBy: 'users')]
    private Collection $usersSocial;

    /**
     * @var Collection<int, Outsiders>
     */
    #[ORM\OneToMany(targetEntity: Outsiders::class, mappedBy: 'users')]
    private Collection $adminOutsiders;

    /**
     * @var Collection<int, Articles>
     */
    #[ORM\OneToMany(targetEntity: Articles::class, mappedBy: 'users')]
    private Collection $blogueursArticle;

    public function __construct()
    {
        $this->adminEvenements = new ArrayCollection();
        $this->usersSocial = new ArrayCollection();
        $this->adminOutsiders = new ArrayCollection();
        $this->blogueursArticle = new ArrayCollection();
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
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
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

    public function setPassword(string $password): static
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

    /**
     * @return Collection<int, Evenements>
     */
    public function getAdminEvenements(): Collection
    {
        return $this->adminEvenements;
    }

    public function addAdminEvenement(Evenements $adminEvenement): static
    {
        if (!$this->adminEvenements->contains($adminEvenement)) {
            $this->adminEvenements->add($adminEvenement);
            $adminEvenement->setUsers($this);
        }

        return $this;
    }

    public function removeAdminEvenement(Evenements $adminEvenement): static
    {
        if ($this->adminEvenements->removeElement($adminEvenement)) {
            // set the owning side to null (unless already changed)
            if ($adminEvenement->getUsers() === $this) {
                $adminEvenement->setUsers(null);
            }
        }

        return $this;
    }

    public function getStreamersPresentation(): ?Presentations
    {
        return $this->streamersPresentation;
    }

    public function setStreamersPresentation(?Presentations $streamersPresentation): static
    {
        $this->streamersPresentation = $streamersPresentation;

        return $this;
    }

    /**
     * @return Collection<int, SocialsNetwork>
     */
    public function getUsersSocial(): Collection
    {
        return $this->usersSocial;
    }

    public function addUsersSocial(SocialsNetwork $usersSocial): static
    {
        if (!$this->usersSocial->contains($usersSocial)) {
            $this->usersSocial->add($usersSocial);
            $usersSocial->setUsers($this);
        }

        return $this;
    }

    public function removeUsersSocial(SocialsNetwork $usersSocial): static
    {
        if ($this->usersSocial->removeElement($usersSocial)) {
            // set the owning side to null (unless already changed)
            if ($usersSocial->getUsers() === $this) {
                $usersSocial->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Outsiders>
     */
    public function getAdminOutsiders(): Collection
    {
        return $this->adminOutsiders;
    }

    public function addAdminOutsider(Outsiders $adminOutsider): static
    {
        if (!$this->adminOutsiders->contains($adminOutsider)) {
            $this->adminOutsiders->add($adminOutsider);
            $adminOutsider->setUsers($this);
        }

        return $this;
    }

    public function removeAdminOutsider(Outsiders $adminOutsider): static
    {
        if ($this->adminOutsiders->removeElement($adminOutsider)) {
            // set the owning side to null (unless already changed)
            if ($adminOutsider->getUsers() === $this) {
                $adminOutsider->setUsers(null);
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
}
