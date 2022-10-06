<?php

namespace App\Tests\Unit\Validator;

use App\Entity\Server;
use App\Entity\Software;
use App\Model\OrderServerModel;
use App\Validator\OrderServer\SoftwareCompatibility;
use App\Validator\OrderServer\SoftwareCompatibilityValidator;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class SoftwareCompatibilityValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidator
    {
        return new SoftwareCompatibilityValidator();
    }

    public function testVPSServerWithoutSoftware(): void
    {
        $model = new OrderServerModel();
        $model->setServer((new Server('test VPS'))->setServerType('VPS'));

        $this->validator->validate($model,new SoftwareCompatibility());

        $this->assertNoViolation();
    }

    public function testSharedServerWithoutSoftware(): void
    {
        $model = new OrderServerModel();
        $model->setServer((new Server('test Mutualisé'))->setServerType('SHARED'));

        $this->validator->validate($model,new SoftwareCompatibility());

        $this->assertNoViolation();
    }

    public function testVPSServerWithSoftware(): void
    {
        $model = new OrderServerModel();
        $model->setServer((new Server('test VPS'))->setServerType('VPS'));
        $model->setSoftwares([new Software('test logiciel')]);

        $this->validator->validate($model,new SoftwareCompatibility());

        $this->buildViolation('Le serveur que vous avez choisi est de type {{ serverType }} : vous ne pouvez donc pas prendre de logiciel')
             ->setParameter('{{ serverType }}', $model->getServerType())
             ->assertRaised();
    }

    public function testSharedServerWithSoftware(): void
    {
        $model = new OrderServerModel();
        $model->setServer((new Server('test Mutualisé'))->setServerType('SHARED'));
        $model->setSoftwares([new Software('test logiciel')]);

        $this->validator->validate($model,new SoftwareCompatibility());

        $this->assertNoViolation();
    }
}
