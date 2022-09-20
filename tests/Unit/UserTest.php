<?php

namespace App\Tests\Unit;

use App\Entity\User;
use App\Exceptions\NotExistentRoleException;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @throws NotExistentRoleException
     */
    public function testWhenNotExistentRoleGiven(): void
    {
        $this->expectException(NotExistentRoleException::class);
        $user = new User();
        $user->setRoles(['ROLE_USER']);
    }
}
