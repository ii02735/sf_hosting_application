<?php

namespace App\Entity;

use App\Repository\ServerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServerRepository::class)
 */
class Server extends Product
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $operatingSystem;

    /**
     * @ORM\Column(type="integer")
     */
    private int $memory;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $serverType;

    public function __construct(string $name)
    {
        $this->setName($name);
        parent::__construct("MONTHLY");
    }

    public function getOperatingSystem(): ?string
    {
        return $this->operatingSystem;
    }

    public function setOperatingSystem(string $operatingSystem): self
    {
        $this->operatingSystem = $operatingSystem;

        return $this;
    }

    public function getMemory(): ?int
    {
        return $this->memory;
    }

    public function setMemory(int $memory): self
    {
        $this->memory = $memory;

        return $this;
    }


    public function setServerType(string $serverType): self
    {
        $this->serverType = $serverType;

        return $this;
    }

    public function getServerType(): string
    {
        return $this->serverType;
    }
}
