<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $entete = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $accroche = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $texte = null;

    #[ORM\ManyToOne(inversedBy: 'blogueursArticle')]
    private ?Users $users = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Users $blogueurArticles = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntete(): ?string
    {
        return $this->entete;
    }

    public function setEntete(string $entete): static
    {
        $this->entete = $entete;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAccroche(): ?string
    {
        return $this->accroche;
    }

    public function setAccroche(string $accroche): static
    {
        $this->accroche = $accroche;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): static
    {
        $this->texte = $texte;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getBlogueurArticles(): ?Users
    {
        return $this->blogueurArticles;
    }

    public function setBlogueurArticles(?Users $blogueurArticles): static
    {
        $this->blogueurArticles = $blogueurArticles;

        return $this;
    }
}
