<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221006000110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates all business tables (client_server, option, ordered_product, product, server, software)';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_server (id INT AUTO_INCREMENT NOT NULL, server_reference VARCHAR(255) NOT NULL, client_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', state VARCHAR(10) NOT NULL, address VARCHAR(20) NOT NULL, INDEX IDX_FCB9E456CB61335 (server_reference), INDEX IDX_FCB9E4519EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (reference VARCHAR(255) NOT NULL, PRIMARY KEY(reference)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, client_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', status VARCHAR(30) NOT NULL, charge_id VARCHAR(255) NOT NULL, INDEX IDX_F529939819EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ordered_product (id VARCHAR(100) NOT NULL, product_reference VARCHAR(255) NOT NULL, origin_order_id INT NOT NULL, INDEX IDX_E6F097B6C003FF9E (product_reference), INDEX IDX_E6F097B6BE2907C8 (origin_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (reference VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(reference)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server (reference VARCHAR(255) NOT NULL, operating_system VARCHAR(100) NOT NULL, memory INT NOT NULL, server_type VARCHAR(50) NOT NULL, PRIMARY KEY(reference)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE software (reference VARCHAR(255) NOT NULL, version VARCHAR(10) NOT NULL, software_type VARCHAR(100) NOT NULL, PRIMARY KEY(reference)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_server ADD CONSTRAINT FK_FCB9E456CB61335 FOREIGN KEY (server_reference) REFERENCES server (reference)');
        $this->addSql('ALTER TABLE client_server ADD CONSTRAINT FK_FCB9E4519EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B0AEA34913 FOREIGN KEY (reference) REFERENCES product (reference) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939819EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE ordered_product ADD CONSTRAINT FK_E6F097B6C003FF9E FOREIGN KEY (product_reference) REFERENCES product (reference)');
        $this->addSql('ALTER TABLE ordered_product ADD CONSTRAINT FK_E6F097B6BE2907C8 FOREIGN KEY (origin_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F6AEA34913 FOREIGN KEY (reference) REFERENCES product (reference) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE software ADD CONSTRAINT FK_77D068CFAEA34913 FOREIGN KEY (reference) REFERENCES product (reference) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_server DROP FOREIGN KEY FK_FCB9E456CB61335');
        $this->addSql('ALTER TABLE client_server DROP FOREIGN KEY FK_FCB9E4519EB6921');
        $this->addSql('ALTER TABLE `option` DROP FOREIGN KEY FK_5A8600B0AEA34913');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939819EB6921');
        $this->addSql('ALTER TABLE ordered_product DROP FOREIGN KEY FK_E6F097B6C003FF9E');
        $this->addSql('ALTER TABLE ordered_product DROP FOREIGN KEY FK_E6F097B6BE2907C8');
        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F6AEA34913');
        $this->addSql('ALTER TABLE software DROP FOREIGN KEY FK_77D068CFAEA34913');
        $this->addSql('DROP TABLE client_server');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE ordered_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE server');
        $this->addSql('DROP TABLE software');
    }
}
