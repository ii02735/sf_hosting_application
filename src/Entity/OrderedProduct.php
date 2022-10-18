<?php

namespace App\Entity;

use App\Exception\NotExistentParameterException;
use App\Repository\OrderedProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderedProductRepository::class)]
class OrderedProduct
{
    #[ORM\Id]
    #[ORM\Column(length: 100)]
    private string $id;


    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: "products")]
    #[ORM\JoinColumn(name: "product_reference", referencedColumnName: "reference", nullable: false)]
    private Product $product;


    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "products")]
    #[ORM\JoinColumn(nullable: false)]
    private Order $originOrder;

    #[ORM\Column(length: 30)]
    private string $status = 'PROCESSING';

    #[ORM\Column(length: 255)]
    private string $stripePaymentId;

    #[ORM\Column(length: 30)]
    private string $billingMethod;


    const ORDER_STATUS = ['PAID','PROCESSING','REFUNDED'];


    public function makeId(): self
    {
        $this->id = sprintf('%s%s%s',date('Ymd'),$this->product->getReference(),explode('-',$this->originOrder->getClient()->getId())[0]);
        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getOriginOrder(): ?Order
    {
        return $this->originOrder;
    }

    public function setOriginOrder(?Order $originOrder): self
    {
        $this->originOrder = $originOrder;

        return $this;
    }

    public function getName(): string
    {
        return $this->product->getName();
    }

    public function getPrice(): float
    {
        return $this->product->getPrice();
    }

    public function pricingMethod(): string
    {
        return $this->product->pricingMethod();
    }

    public function getStripePaymentId(): ?string
    {
        return $this->stripePaymentId;
    }

    /**
     * @param string $status
     * @return OrderedProduct
     * @throws NotExistentParameterException
     */
    public function setStatus(string $status): self
    {
        if(!in_array($status,self::ORDER_STATUS))
            throw new NotExistentParameterException(sprintf("Invalid order status '%s'",$status));

        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStripePaymentId(string $stripePaymentId): self
    {
        $this->stripePaymentId = $stripePaymentId;
        $this->status = 'PAID';

        return $this;
    }

    /**
     * @return string
     */
    public function getBillingMethod(): string
    {
        return $this->billingMethod;
    }

    /**
     * @param string $billingMethod
     */
    public function setBillingMethod(string $billingMethod): void
    {
        $this->billingMethod = $billingMethod;
    }

}
