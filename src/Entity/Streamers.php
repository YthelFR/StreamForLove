<?php

namespace App\Entity;

use App\Repository\StreamersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StreamersRepository::class)]
class Streamers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $pronoms = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lien = null;
    public function __construct()
    {
        $this->socialsNetworks = new ArrayCollection();
        $this->evenements = new ArrayCollection();
        $this->cagnottes = new ArrayCollection();
    }

    #[ORM\Column]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: SocialsNetwork::class)]
    private Collection $socialsNetworks;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Cagnotte::class)] 
    private Collection $cagnottes;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPronoms(): ?string
    {
        return $this->pronoms;
    }

    public function setPronoms(?string $pronoms): static
    {
        $this->pronoms = $pronoms;

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(?string $lien): static
    {
        $this->lien = $lien;

        return $this;
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
}
