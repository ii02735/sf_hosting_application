<?php

namespace App\Tests\Unit\Form;

use App\Entity\Option;
use App\Entity\Server;
use App\Entity\Software;
use App\Form\OrderServerType;
use App\Model\OrderServerModel;
use App\Repository\OptionRepository;
use App\Repository\ServerRepository;
use App\Repository\SoftwareRepository;
use App\Tests\Unit\Validator\SoftwareCompatibilityValidatorTest;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use function PHPUnit\Framework\assertTrue;

class OrderServerTypeTest extends TypeTestCase
{
    private OptionRepository|MockObject $optionRepository;
    private ServerRepository|MockObject $serverRepository;
    private SoftwareRepository|MockObject $softwareRepository;

    private static array $possibleOptions, $possibleSoftwares, $possibleServers;

    public static function setUpBeforeClass(): void
    {
        self::$possibleOptions = [
            (new Option('Option de sauvegarde'))->setReference('BACKUP'),
            (new Option('Option de snapshot'))->setReference('SNAPSHOT')
        ];

        self::$possibleSoftwares = [
            (new Software('CMS Wordpress'))->setSoftwareType('CMS')->setReference('WORDPRESS'),
            (new Software('Plateforme Node.js'))->setSoftwareType('Interpreter')->setReference('NodeJS'),
            (new Software('Interpréteur PHP'))->setSoftwareType('Interpreter')->setReference('PHP'),
            (new Software('Interpréteur Perl'))->setSoftwareType('Interpreter')->setReference('Perl'),
            (new Software('Interpréteur Python'))->setSoftwareType('Interpreter')->setReference('Python'),
            (new Software('Apache'))->setSoftwareType('Web Server')->setReference('Apache'),
            (new Software('Nginx'))->setSoftwareType('Web Server')->setReference('Nginx'),
        ];

        self::$possibleServers = [
            (new Server('VPS Debian'))->setReference('VPSDEBIAN001')->setServerType('VPS'),
            (new Server('VPS Debian'))->setReference('VPSALPINE002')->setServerType('VPS'),
            (new Server('VPS Debian'))->setReference('VPSALPINE003')->setServerType('VPS'),
            (new Server('Shared Debian'))->setReference('SHAREDDEBIAN001')->setServerType('SHARED')
        ];
    }

    protected function getExtensions(): array
    {
        $this->optionRepository = $this->createMock(OptionRepository::class);
        $this->serverRepository = $this->createMock(ServerRepository::class);
        $this->softwareRepository = $this->createMock(SoftwareRepository::class);

        $type = new OrderServerType($this->optionRepository,$this->serverRepository,$this->softwareRepository);

        // or if you also need to read constraints from annotations
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping(true)
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator();

        return array_merge(
            parent::getExtensions(),
            [new PreloadedExtension([$type],[]), new ValidatorExtension($validator)]
        );
    }

    private function providePossibleSoftwares(): void
    {
        $this->softwareRepository->expects($this->exactly(1))
            ->method("findAll")
            ->willReturn(self::$possibleSoftwares);
    }

    private function providePossibleServers(): void
    {
        $this->serverRepository->expects($this->exactly(1))
            ->method("findAll")
            ->willReturn(self::$possibleServers);
    }

    private function providePossibleOptions(): void
    {
        $this->optionRepository->expects($this->exactly(1))
             ->method("findAll")
             ->willReturn(self::$possibleOptions);
    }


    public function testInvalidationWhenNoServerIsProvided()
    {
        $this->providePossibleServers();

        $model = new OrderServerModel();
        $form = $this->factory->create(OrderServerType::class, $model);

        // Submit an empty array, then without server
        $form->submit([]);
        $this->assertFalse($form->isValid());
    }

    public function testModelContentWithOnlyOneServer()
    {
        $this->providePossibleServers();

        $model = new OrderServerModel();
        $form = $this->factory->create(OrderServerType::class, $model);

        $formData = [
            'server' => 'VPSDEBIAN001'
        ];

        $form->submit($formData);

        $expectedModel = new OrderServerModel();
        $expectedModel->setServer(self::$possibleServers[0]);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertEquals($expectedModel,$model);
    }

    public function testInvalidationWhenOptionIsProvidedWithoutServer()
    {
        $this->providePossibleOptions();

        $form = $this->factory->create(OrderServerType::class, new OrderServerModel);

        $formData = [
            'options' => ['BACKUP']
        ];

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());

    }

    public function testWhenOptionIsProvidedWithServer()
    {
        $this->providePossibleServers();
        $this->providePossibleOptions();

        $form = $this->factory->create(OrderServerType::class, new OrderServerModel);

        $formData = [
            'server' => 'VPSDEBIAN001',
            'options' => ['BACKUP']
        ];

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }

    public function testInvalidationWhenSoftwareIsProvidedWithoutServer()
    {
        $this->providePossibleSoftwares();

        $form = $this->factory->create(OrderServerType::class, new OrderServerModel);

        $formData = [
            'softwares' => ['NodeJS']
        ];

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testInvalidationWhenMaximumSoftwareIsMet()
    {
        $this->providePossibleServers();
        $this->providePossibleSoftwares();

        $form = $this->factory->create(OrderServerType::class, new OrderServerModel);

        $formData = [
            'server' => 'SHAREDDEBIAN001',
            'softwares' => ['PHP','Perl','NodeJS','Nginx','Python','WORDPRESS']
        ];

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());

        $this->assertEquals("ERROR: Vous pouvez choisir jusqu'à 5 logiciels seulement\n",
            (string) $form->getErrors(true));
    }

    /**
     * @see SoftwareCompatibilityValidatorTest for more precise test cases
     */
    public function testInvalidationWhenServerAndSoftwareAreNotCompatible()
    {
        $this->providePossibleServers();
        $this->providePossibleSoftwares();

        $form = $this->factory->create(OrderServerType::class, new OrderServerModel);

        $formData = [
            'server' => 'VPSDEBIAN001',
            'softwares' => ['WORDPRESS']
        ];

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());

        $this->assertEquals("ERROR: Le serveur que vous avez choisi est de type VPS : vous ne pouvez donc pas prendre de logiciel\n",
            (string) $form->getErrors(true));
    }

    public function testInvalidationWhenMultipleServerWebAreChosen()
    {
        $this->providePossibleServers();
        $this->providePossibleSoftwares();

        $form = $this->factory->create(OrderServerType::class, new OrderServerModel());

        $formData = [
            'server' => 'SHAREDDEBIAN001',
            'softwares' => ['Apache', 'Nginx']
        ];

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());

        $this->assertEquals("ERROR: Vous ne pouvez pas prendre plusieurs serveurs web\n",
            (string) $form->getErrors(true));
    }

    public function testWhenServerWithOptionsAndSoftwaresAreChosen()
    {
        $this->providePossibleServers();
        $this->providePossibleSoftwares();
        $this->providePossibleOptions();

        $model = new OrderServerModel();

        $form = $this->factory->create(OrderServerType::class, $model);

        $formData = [
            'server' => 'SHAREDDEBIAN001',
            'softwares' => ['Apache', 'PHP', 'Perl'],
            'options' => ['BACKUP', 'SNAPSHOT']
        ];

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());




    }
}
