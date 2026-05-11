<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260511170035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_evenement (user_id INT NOT NULL, evenement_id INT NOT NULL, PRIMARY KEY (user_id, evenement_id))');
        $this->addSql('CREATE INDEX IDX_BC6E5FAA76ED395 ON user_evenement (user_id)');
        $this->addSql('CREATE INDEX IDX_BC6E5FAFD02F13 ON user_evenement (evenement_id)');
        $this->addSql('ALTER TABLE user_evenement ADD CONSTRAINT FK_BC6E5FAA76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_evenement ADD CONSTRAINT FK_BC6E5FAFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement ADD categorie VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD description VARCHAR(500) DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD replay_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD duree INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD filiere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E180AA129 FOREIGN KEY (filiere_id) REFERENCES filiere (id)');
        $this->addSql('CREATE INDEX IDX_B26681E180AA129 ON evenement (filiere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_evenement DROP CONSTRAINT FK_BC6E5FAA76ED395');
        $this->addSql('ALTER TABLE user_evenement DROP CONSTRAINT FK_BC6E5FAFD02F13');
        $this->addSql('DROP TABLE user_evenement');
        $this->addSql('ALTER TABLE evenement DROP CONSTRAINT FK_B26681E180AA129');
        $this->addSql('DROP INDEX IDX_B26681E180AA129');
        $this->addSql('ALTER TABLE evenement DROP categorie');
        $this->addSql('ALTER TABLE evenement DROP description');
        $this->addSql('ALTER TABLE evenement DROP replay_url');
        $this->addSql('ALTER TABLE evenement DROP duree');
        $this->addSql('ALTER TABLE evenement DROP filiere_id');
    }
}
