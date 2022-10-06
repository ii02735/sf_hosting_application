<?php

namespace App\Tests\Unit;

use App\Entity\User;
use App\Exception\NotExistentParameterException;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    /**
     * @throws NotExistentParameterException
     */
    public function testWhenGivenRoleNotExistent(): void
    {
        $this->expectException(NotExistentParameterException::class);
        $user = new User();
        $user->setRoles(['USER']);
    }

}
