<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    protected File $picture;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?OAuth2ClientProfile $OAuth2ClientProfile = null;

    #[ORM\Column(length: 255)]
    private ?string $picturePath = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function __construct() {
        // $this->setOAuth2ClientProfile(new OAuth2ClientProfile());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        $this->setSlug();

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getOAuth2ClientProfile(): ?OAuth2ClientProfile
    {
        return $this->OAuth2ClientProfile;
    }

    public function setOAuth2ClientProfile(OAuth2ClientProfile $OAuth2ClientProfile): static
    {
        $this->OAuth2ClientProfile = $OAuth2ClientProfile;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(File $picture = null): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    private function setSlug(): static
    {
        $slugger = new AsciiSlugger();
        $this->slug = $slugger->slug($this->name)->toString();

        return $this;
    }
}
