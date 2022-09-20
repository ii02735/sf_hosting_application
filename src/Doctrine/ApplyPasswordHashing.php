<?php

namespace App\Doctrine;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

interface ApplyPasswordHashing
{
    public function setHashing(UserPasswordHasherInterface $service);
}