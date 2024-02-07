<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207135342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE oauth2_user_consent DROP FOREIGN KEY FK_C8F05D01A76ED395');
        $this->addSql('ALTER TABLE oauth2_user_consent DROP FOREIGN KEY FK_C8F05D0119EB6921');
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE oauth2_user_consent CHANGE user_id user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('DROP INDEX UNIQ_8D93D649D17F50A6 ON user');
        $this->addSql('ALTER TABLE user DROP uuid, CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE oauth2_user_consent ADD CONSTRAINT FK_C8F05D01A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE oauth2_user_consent ADD CONSTRAINT FK_C8F05D0119EB6921 FOREIGN KEY (client_id) REFERENCES oauth2_client (identifier)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE oauth2_user_consent CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE id id INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D17F50A6 ON user (uuid)');
    }
}
