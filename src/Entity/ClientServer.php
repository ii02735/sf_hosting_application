<?php

namespace App\Entity;

use App\Repository\ClientServerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClientServerRepository::class)
 */
class ClientServer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Server::class)
     * @ORM\JoinColumn(nullable=false, name="server_reference", referencedColumnName="reference")
     */
    private $server;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="clientServers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServer(): ?Server
    {
        return $this->server;
    }

    public function setServer(?Server $server): self
    {
        $this->server = $server;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }
}
