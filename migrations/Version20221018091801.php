<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221018091801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Renaming charge_id by stripe_payment_id (because it can either be a payment ID or a subscription ID) + adding billing method (to differ rows with same stripe_payment_id)';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD created DATETIME NOT NULL, ADD updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE ordered_product ADD billing_method VARCHAR(30) NOT NULL, CHANGE charge_id stripe_payment_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP created, DROP updated');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE ordered_product DROP billing_method, CHANGE stripe_payment_id charge_id VARCHAR(255) NOT NULL');
    }
}
