<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703124030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id VARCHAR(26) NOT NULL, name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(256) NOT NULL, password VARCHAR(100) NOT NULL, salt VARCHAR(32) NOT NULL, verify_email_token VARCHAR(32) NOT NULL, verified_email TINYINT(1) NOT NULL, newsletter TINYINT(1) NOT NULL, terms_accepted TINYINT(1) NOT NULL, access_token VARCHAR(512) DEFAULT NULL, refresh_token VARCHAR(512) DEFAULT NULL, reset_password_token VARCHAR(512) DEFAULT NULL, reset_password_token_expires_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX unique_email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
    }
}
