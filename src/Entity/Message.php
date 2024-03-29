<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conversation $conversation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sender = null;

    #[ORM\Column(length: 1000)]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'replyOn')]
    private ?self $reply = null;

    #[ORM\OneToMany(mappedBy: 'reply', targetEntity: self::class)]
    private Collection $replyOn;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $isRead = null;

    #[ORM\Column]
    private ?bool $isFriendRequest = null;

    public function __construct(User $sender, string $content, Conversation $conversation)
    {
        $this->setSender($sender)->setIsRead(false)->setIsFriendRequest(false);
        $this->setConversation($conversation)->setContent($content);
        $this->replyOn = new ArrayCollection();
        $this->setCreatedAt(new \DateTimeImmutable('now'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): static
    {
        $this->conversation = $conversation;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getReply(): ?self
    {
        return $this->reply;
    }

    public function setReply(?self $reply): static
    {
        $this->reply = $reply;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getRepliesOn(): Collection
    {
        return $this->replyOn;
    }

    public function addReplyOn(self $replyOn): static
    {
        if (!$this->replyOn->contains($replyOn)) {
            $this->replyOn->add($replyOn);
            $replyOn->setReply($this);
        }

        return $this;
    }

    public function removeReplyOn(self $replyOn): static
    {
        if ($this->replyOn->removeElement($replyOn)) {
            // set the owning side to null (unless already changed)
            if ($replyOn->getReply() === $this) {
                $replyOn->setReply(null);
            }
        }

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

    public function isIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): static
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function isIsFriendRequest(): ?bool
    {
        return $this->isFriendRequest;
    }

    public function setIsFriendRequest(bool $isFriendRequest): static
    {
        $this->isFriendRequest = $isFriendRequest;

        return $this;
    }
}
