<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $receiver = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sender = null;

    #[ORM\Column]
    private ?bool $deletedReceiver = null;

    #[ORM\Column]
    private ?bool $deletedSender = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'reaction')]
    private ?self $reaction = null;

    #[ORM\Column]
    private ?bool $seen = null;

    public function __construct()
    {
        $this->reaction = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): static
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function isDeletedReceiver(): ?bool
    {
        return $this->deletedReceiver;
    }

    public function setDeletedReceiver(bool $deletedReceiver): static
    {
        $this->deletedReceiver = $deletedReceiver;

        return $this;
    }

    public function isDeletedSender(): ?bool
    {
        return $this->deletedSender;
    }

    public function setDeletedSender(bool $deletedSender): static
    {
        $this->deletedSender = $deletedSender;

        return $this;
    }

    public function getReaction(): ?self
    {
        return $this->reaction;
    }

    public function setReaction(?self $reaction): static
    {
        $this->reaction = $reaction;

        return $this;
    }

    public function addReaction(self $reaction): static
    {
        if (!$this->reaction->contains($reaction)) {
            $this->reaction->add($reaction);
            $reaction->setReaction($this);
        }

        return $this;
    }

    public function removeReaction(self $reaction): static
    {
        if ($this->reaction->removeElement($reaction)) {
            // set the owning side to null (unless already changed)
            if ($reaction->getReaction() === $this) {
                $reaction->setReaction(null);
            }
        }

        return $this;
    }

    public function isSeen(): ?bool
    {
        return $this->seen;
    }

    public function setSeen(bool $seen): static
    {
        $this->seen = $seen;

        return $this;
    }
}
