<?php

namespace App\Entity;

use App\Repository\SoftwareRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SoftwareRepository::class)]
class Software extends Product
{

    #[ORM\Column(length: 10)]
    private string $version;

    #[ORM\Column(length: 100)]
    private string $softwareType;

    public function __construct(string $name)
    {
        $this->setName($name);
        parent::__construct('ONE-SHOT');
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    public function getSoftwareType(): ?string
    {
        return $this->softwareType;
    }

    public function setSoftwareType(string $softwareType): self
    {
        $this->softwareType = $softwareType;

        return $this;
    }
}
