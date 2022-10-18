<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Exception\NotExistentParameterException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLES = ["ROLE_CLIENT","ROLE_ADMIN"];

    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $id;

    #[ORM\Column(length: 100, unique: true)]
    private string $email;

    #[ORM\Column(type: "json")]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private string $password;

    #[ORM\OneToMany(mappedBy: "client", targetEntity: Order::class, orphanRemoval: true)]
    private Collection $orders;

    #[ORM\OneToMany(mappedBy: "client", targetEntity: ClientServer::class, orphanRemoval: true)]
    private Collection $clientServers;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->orders = new ArrayCollection();
        $this->clientServers = new ArrayCollection();
    }


    public function setId(UuidInterface $uuid): self
    {
        $this->id = $uuid;

        return $this;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }


    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    /**
     * @throws NotExistentParameterException
     */
    public function setRoles(array $roles): self
    {
        foreach($roles as $role)
        {
            if(!in_array($role,self::ROLES))
                throw new NotExistentParameterException(sprintf("Invalid role '%s'",$role));
        }
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setClient($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getClient() === $this) {
                $order->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ClientServer>
     */
    public function getClientServers(): Collection
    {
        return $this->clientServers;
    }

    public function addClientServer(ClientServer $clientServer): self
    {
        if (!$this->clientServers->contains($clientServer)) {
            $this->clientServers[] = $clientServer;
            $clientServer->setClient($this);
        }

        return $this;
    }

    public function removeClientServer(ClientServer $clientServer): self
    {
        if ($this->clientServers->removeElement($clientServer)) {
            // set the owning side to null (unless already changed)
            if ($clientServer->getClient() === $this) {
                $clientServer->setClient(null);
            }
        }

        return $this;
    }
}
