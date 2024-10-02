<?php

namespace App\Entity;

use App\Repository\OutsidersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OutsidersRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_PSEUDO', fields: ['pseudo'])]
class Outsiders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $twitch = null;

    #[ORM\Column]
    private ?int $somme = null;

    #[ORM\ManyToOne(inversedBy: 'outsiders')]
    private ?Users $user = null;

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

    public function getTwitch(): ?string
    {
        return $this->twitch;
    }

    public function setTwitch(string $twitch): static
    {
        $this->twitch = $twitch;

        return $this;
    }

    public function getSomme(): ?int
    {
        return $this->somme;
    }

    public function setSomme(int $somme): static
    {
        $this->somme = $somme;
        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;
        return $this;
    }
}
