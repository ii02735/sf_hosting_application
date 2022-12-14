<?php

namespace App\Doctrine;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Version\MigrationFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * This class has the purpose to inject
 * the UserPasswordHasherInterface service when the
 * ApplyPasswordHashing is implemented into the Migration class
 */

class PasswordHashingMigrationFactory implements MigrationFactory
{
    private MigrationFactory $migrationFactory;
    private UserPasswordHasherInterface $service;

    public function __construct(MigrationFactory $migrationFactory, UserPasswordHasherInterface $service)
    {
        $this->migrationFactory = $migrationFactory;
        $this->service = $service;
    }

    public function createVersion(string $migrationClassName): AbstractMigration
    {
        $migration = $this->migrationFactory->createVersion($migrationClassName);

        /**
         * Here is the logic where an additional service
         * might me injected if an interface is implemented into
         * the migration class
         */

        if($migration instanceof ApplyPasswordHashing)
            $migration->setHashing($this->service);

        return $migration;
    }
}