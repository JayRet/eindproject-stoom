<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator = null;

    #[ORM\Column]
    private ?bool $private = null;

    #[ORM\Column(length: 190)]
    private ?string $url = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Score::class)]
    private Collection $scores;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Achievement::class)]
    private Collection $achievements;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: GameMedia::class)]
    private Collection $gameMedia;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
        $this->achievements = new ArrayCollection();
        $this->gameMedia = new ArrayCollection();
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

    public function isPrivate(): ?bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): static
    {
        $this->private = $private;

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

    /**
     * @return Collection<int, Score>
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): static
    {
        if (!$this->scores->contains($score)) {
            $this->scores->add($score);
            $score->setGame($this);
        }

        return $this;
    }

    public function removeScore(Score $score): static
    {
        if ($this->scores->removeElement($score)) {
            // set the owning side to null (unless already changed)
            if ($score->getGame() === $this) {
                $score->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Achievement>
     */
    public function getAchievements(): Collection
    {
        return $this->achievements;
    }

    public function addAchievement(Achievement $achievement): static
    {
        if (!$this->achievements->contains($achievement)) {
            $this->achievements->add($achievement);
            $achievement->setGame($this);
        }

        return $this;
    }

    public function removeAchievement(Achievement $achievement): static
    {
        if ($this->achievements->removeElement($achievement)) {
            // set the owning side to null (unless already changed)
            if ($achievement->getGame() === $this) {
                $achievement->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GameMedia>
     */
    public function getGameMedia(): Collection
    {
        return $this->gameMedia;
    }

    public function addGameMedium(GameMedia $gameMedium): static
    {
        if (!$this->gameMedia->contains($gameMedium)) {
            $this->gameMedia->add($gameMedium);
            $gameMedium->setGame($this);
        }

        return $this;
    }

    public function removeGameMedium(GameMedia $gameMedium): static
    {
        if ($this->gameMedia->removeElement($gameMedium)) {
            // set the owning side to null (unless already changed)
            if ($gameMedium->getGame() === $this) {
                $gameMedium->setGame(null);
            }
        }

        return $this;
    }
}
