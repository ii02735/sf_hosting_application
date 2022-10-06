<?php

namespace App\Tests\Unit;

use App\Entity\Option;
use App\Entity\Order;
use App\Entity\OrderedProduct;
use App\Entity\Product;
use App\Entity\Server;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    private Order $order;

    protected function setUp(): void
    {
        $this->order = new Order;
    }

    public function testCreateEmptyOrder()
    {
        $this->assertEquals(0,$this->order->getTotalPrice());
        $this->assertCount(0, $this->order->getProducts());
    }

    public function testAddProductToOrder()
    {
        $this->order->addProduct(new OrderedProduct);
        $this->assertCount(1, $this->order->getProducts());
    }

    public function testReturnPriceWithOneOrderedProduct()
    {
        $product = new Server('test serveur');
        $product->setPrice(20);
        $orderedProduct = new OrderedProduct();
        $orderedProduct->setProduct($product);
        $this->order->addProduct($orderedProduct);
        $this->assertEquals(20, $this->order->getTotalPrice());
    }

    public function testReturnPriceWithMultipleProduct()
    {
        $product1 = new Server('test serveur');
        $product2 = new Option('test option');
        $product1->setPrice(20);
        $product2->setPrice(40);

        $orderedProduct = new OrderedProduct();
        $orderedProduct->setProduct($product1);
        $this->order->addProduct($orderedProduct);

        $orderedProduct = new OrderedProduct();
        $orderedProduct->setProduct($product2);
        $this->order->addProduct($orderedProduct);

        $this->assertEquals(60, $this->order->getTotalPrice());
    }
}
