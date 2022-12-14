<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "orders")]
    #[ORM\JoinColumn(nullable: false)]
    private User $client;

    #[ORM\OneToMany(mappedBy: "originOrder", targetEntity: OrderedProduct::class, orphanRemoval: true)]
    private Collection $products;

    #[Gedmo\Timestampable(on: "create")]
    #[ORM\Column(type: "datetime")]
    private \DateTime $created;

    #[Gedmo\Timestampable(on: "update")]
    #[ORM\Column(type: "datetime")]
    private \DateTime $updated;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return Order
     */
    public function setCreated(\DateTime $created): Order
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     * @return Order
     */
    public function setUpdated(\DateTime $updated): Order
    {
        $this->updated = $updated;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, OrderedProduct>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(OrderedProduct $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setOriginOrder($this);
        }

        return $this;
    }

    public function removeProduct(OrderedProduct $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getOriginOrder() === $this) {
                $product->setOriginOrder(null);
            }
        }

        return $this;
    }

    public function getTotalPrice(): float
    {
        $price = 0;
        /**
         * @var OrderedProduct $product
         */
        foreach ($this->products as $product)
        {
            $price += $product->getPrice();
        }

        return $price;
    }
}
