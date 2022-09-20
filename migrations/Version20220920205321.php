<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Doctrine\ApplyPasswordHashing;
use App\Entity\User;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220920205321 extends AbstractMigration implements ApplyPasswordHashing
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function getDescription(): string
    {
        return 'Generate an admin user';
    }

    public function up(Schema $schema): void
    {
        $adminPassword = $this->userPasswordHasher->hashPassword(new User(),'ADMIN_PASSWORD');
        $id = Uuid::uuid4()->toString();
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `user` (id,email,password,roles) VALUES ('$id','admin@hosting.net','$adminPassword',JSON_ARRAY('ADMIN'))");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE `user` WHERE email = 'admin@hosting.net'");

    }

    public function setHashing(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
}
