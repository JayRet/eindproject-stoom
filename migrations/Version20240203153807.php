<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240203153807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE oauth2_user_consent (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, client_id VARCHAR(32) NOT NULL, created DATETIME NOT NULL, expires DATETIME NOT NULL, scopes LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', INDEX IDX_C8F05D01A76ED395 (user_id), INDEX IDX_C8F05D0119EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE oauth2_user_consent ADD CONSTRAINT FK_C8F05D01A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE oauth2_user_consent ADD CONSTRAINT FK_C8F05D0119EB6921 FOREIGN KEY (client_id) REFERENCES oauth2_client (identifier)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE oauth2_user_consent DROP FOREIGN KEY FK_C8F05D01A76ED395');
        $this->addSql('ALTER TABLE oauth2_user_consent DROP FOREIGN KEY FK_C8F05D0119EB6921');
        $this->addSql('DROP TABLE oauth2_user_consent');
    }
}
