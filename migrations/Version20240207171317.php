<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207171317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE oauth2_client_profile ADD apikey BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', ADD picture_path VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B524E1FB84757A1 ON oauth2_client_profile (apikey)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_9B524E1FB84757A1 ON oauth2_client_profile');
        $this->addSql('ALTER TABLE oauth2_client_profile DROP apikey, DROP picture_path');
    }
}
