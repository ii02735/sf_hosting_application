<?php

namespace App\Tests\Unit;

use App\Entity\Option;
use App\Entity\Product;
use App\Entity\Server;
use App\Entity\Software;
use App\Entity\User;
use App\Exception\NotExistentParameterException;
use App\Repository\ProductRepository;
use App\Services\OrderManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Security;

class OrderManagerTest extends TestCase
{
    private OrderManager|MockObject $orderManager;
    private Security|MockObject $security;
    private static User $user;

    public static function setUpBeforeClass(): void
    {
        self::$user = new User();
        self::$user->setEmail('test@client.net');
        self::$user->setId(Uuid::uuid4());
    }

    protected function setUp(): void
    {
        $this->security = $this->createMock(Security::class);
        $this->repository = $this->createMock(ProductRepository::class);
        $this->orderManager = new OrderManager($this->security);
    }

    public function testRetrieveCurrentUserWhenCreatingOrder(): void
    {
        $this->security->expects($this->exactly(1))
                       ->method('getUser')
                       ->willReturn(self::$user);

        $order = $this->orderManager->createOrder();
        $this->assertEquals(self::$user->getEmail(),$order->getClient()->getEmail());
    }


    /**
     * @throws NotExistentParameterException
     * Must return "MONTHLY" pricing method when server product is ordered
     */
    public function testPricingMethodObtainedFromOrderCreationForServerProduct(): void
    {
        $this->security->expects($this->exactly(1))
            ->method('getUser')
            ->willReturn(self::$user);

        $product = new Server('test VPS');
        $product->setReference('VPSDEBIAN001');
        $order = $this->orderManager->createOrder($product);
        $this->assertEquals('MONTHLY',$order->getProducts()->get(0)->pricingMethod());
    }

    /**
     * @throws NotExistentParameterException
     * Must return "ONE-SHOT" pricing method when an option and a software product are ordered
     */
    public function testPricingMethodObtainedFromOrderCreationForOptionAndSoftwareProducts(): void
    {
        $this->security->expects($this->exactly(1))
            ->method('getUser')
            ->willReturn(self::$user);

        $optionProduct = new Option('test option');
        $softwareProduct = new Software('test logiciel');

        $optionProduct->setReference('BACKUP');
        $softwareProduct->setReference('WORDPRESS');

        $order = $this->orderManager->createOrder($optionProduct,$softwareProduct);


        $this->assertEquals('MONTHLY',$order->getProducts()->get(0)->pricingMethod());
        $this->assertEquals('ONE-SHOT',$order->getProducts()->get(1)->pricingMethod());
    }




}
