<?php

namespace App\Entity;

use App\Repository\FriendRequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FriendRequestRepository::class)]
class FriendRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'friends')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sender = null;

    #[ORM\ManyToOne(inversedBy: 'friends')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $receiver = null;

    #[ORM\Column]
    private ?bool $accepted = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateAccepted = null;

    public function __construct(User $sender, User $receiver)
    {
        $this->setSender($sender)->setReceiver($receiver)->setAccepted(false);
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): static
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function isAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function setAccepted(bool $accepted): static
    {
        $this->accepted = $accepted;
        if ($accepted) {
            $this->sender->addFriend($this->receiver);
            $this->receiver->addFriend($this->sender);
        }

        return $this;
    }

    public function getDateAccepted(): ?\DateTimeImmutable
    {
        return $this->dateAccepted;
    }

    public function setDateAccepted(\DateTimeImmutable $dateAccepted): static
    {
        $this->dateAccepted = $dateAccepted;

        return $this;
    }
}
