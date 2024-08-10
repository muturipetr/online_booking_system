<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240802093700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookings DROP email');
        $this->addSql('ALTER TABLE services CHANGE name name VARCHAR(55) NOT NULL, CHANGE description description VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE user ADD email VARCHAR(180) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookings ADD email VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE services CHANGE name name VARCHAR(100) NOT NULL, CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE `user` DROP email');
    }
}
