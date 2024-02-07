<?php

namespace App\Entity;

use App\Repository\OAuth2ClientProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OAuth2ClientProfileRepository::class)]
class OAuth2ClientProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(referencedColumnName: 'identifier', nullable: false)]
    private ?Client $client = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $apikey = null;

    #[ORM\Column(length: 255)]
    private ?string $picturePath = null;

    public function __construct(private UuidFactory $uuidFactory) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): static
    {
        $this->client = $client;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getApikey(): ?Uuid
    {
        return $this->apikey->toRfc4122();
    }

    public function generateApikey(Uuid $apikey): static
    {
        $this->apikey = $this->uuidFactory->create();

        return $this;
    }

    public function getPicturePath(): ?string
    {
        return $this->picturePath;
    }

    public function setPicturePath(string $picturePath): static
    {
        $this->picturePath = $picturePath;

        return $this;
    }
}
