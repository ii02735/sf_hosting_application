<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserRole;
use App\Exceptions\NotExistentRoleException;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * @throws NotExistentRoleException
     */
    public function load(ObjectManager $manager): void
    {
       $client = new User();
       $client->setRoles(['CLIENT']);
       $client->setEmail(sprintf("testclient_%d@mail.net",date('dmyHms')));

       $password = $this->userPasswordHasher->hashPassword($client,"testclient");

       $client->setPassword($password);
       $manager->persist($client);

       $manager->flush();
    }
}
