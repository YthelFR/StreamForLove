<?php

namespace App\Entity;

use App\Repository\PresentationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PresentationsRepository::class)]
class Presentations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[ORM\Column(length: 255, nullable: true)]
    // private ?string $picture = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $question1 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $question2 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $question3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clip1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clip2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clip3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clip4 = null;

    #[ORM\OneToOne(targetEntity: Users::class, inversedBy: 'streamersPresentation', cascade: ['persist', 'remove'])]
    private ?Users $streamersPresentation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $planning = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $picturePath = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getPicture(): ?string
    // {
    //     return $this->picture;
    // }

    // public function setPicture(string $picture): static
    // {
    //     $this->picture = $picture;

    //     return $this;
    // }

    public function getQuestion1(): ?string
    {
        return $this->question1;
    }

    public function setQuestion1(string $question1): static
    {
        $this->question1 = $question1;

        return $this;
    }

    public function getQuestion2(): ?string
    {
        return $this->question2;
    }

    public function setQuestion2(string $question2): static
    {
        $this->question2 = $question2;

        return $this;
    }

    public function getQuestion3(): ?string
    {
        return $this->question3;
    }

    public function setQuestion3(string $question3): static
    {
        $this->question3 = $question3;

        return $this;
    }

    public function getClip1(): ?string
    {
        return $this->clip1;
    }

    public function setClip1(?string $clip1): static
    {
        $this->clip1 = $clip1;

        return $this;
    }

    public function getClip2(): ?string
    {
        return $this->clip2;
    }

    public function setClip2(?string $clip2): static
    {
        $this->clip2 = $clip2;

        return $this;
    }

    public function getClip3(): ?string
    {
        return $this->clip3;
    }

    public function setClip3(?string $clip3): static
    {
        $this->clip3 = $clip3;

        return $this;
    }

    public function getClip4(): ?string
    {
        return $this->clip4;
    }

    public function setClip4(?string $clip4): static
    {
        $this->clip4 = $clip4;

        return $this;
    }

    public function getStreamersPresentation(): ?Users
    {
        return $this->streamersPresentation;
    }

    public function setStreamersPresentation(?Users $streamersPresentation): static
    {
        $this->streamersPresentation = $streamersPresentation;

        return $this;
    }

    public function getPlanning(): ?string
    {
        return $this->planning;
    }

    public function setPlanning(string $planning): static
    {
        $this->planning = $planning;

        return $this;
    }

    public function getPicturePath(): ?string
    {
        return $this->picturePath;
    }

    public function setPicturePath(?string $picturePath): self
    {
        $this->picturePath = $picturePath;
        return $this;
    }
}
