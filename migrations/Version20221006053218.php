<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221006053218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'The Order entity won\'keep details regarding Stripe, instead those will be sent transferred to the OrderedProduct entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP status, DROP charge_id');
        $this->addSql('ALTER TABLE ordered_product ADD status VARCHAR(30) NOT NULL, ADD charge_id VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD status VARCHAR(30) NOT NULL, ADD charge_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ordered_product DROP status, DROP charge_id');
    }
}
