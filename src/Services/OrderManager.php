<?php

namespace App\Services;

use App\Entity\Order;
use App\Entity\OrderedProduct;
use App\Entity\Product;
use Symfony\Component\Security\Core\Security;

/**
 * This class has the responsibility
 * to manage orders (creation, cancellation)
 */
class OrderManager
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Will create an order for the connected User
     * @param Product ...$products A collection of products
     * @return Order An order object that will be used for the Stripe transaction
     */
    public function createOrder(Product...$products): Order
    {
        $user = $this->security->getUser();
        $order = new Order();
        $order->setClient($user);
        foreach ($products as $product)
        {
            $orderedProduct = new OrderedProduct();
            $orderedProduct->setProduct($product);
            $orderedProduct->setOriginOrder($order);
            $orderedProduct->makeId();
            $order->addProduct($orderedProduct);
        }

        return $order;
    }

}