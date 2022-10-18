<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({ "server" = "Server", "option" = "Option", "software" = "Software" })
 */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap(["server" => "Server", "option" => "Option", "software" => "Software"])]
class Product
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private string $reference;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private float $price;

    #[ORM\Column(length: 255, nullable: true)]
    private string $description;

    protected string $pricingMethod;

    public function __construct(string $pricingMethod)
    {
        $this->pricingMethod = $pricingMethod;
    }

    public function pricingMethod(): string {

        return $this->pricingMethod;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }




}
