<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221226043327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contas ADD correntista_id INT NOT NULL');
        $this->addSql('ALTER TABLE contas ADD CONSTRAINT FK_192B0617689E9E7D FOREIGN KEY (correntista_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_192B0617689E9E7D ON contas (correntista_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contas DROP FOREIGN KEY FK_192B0617689E9E7D');
        $this->addSql('DROP INDEX IDX_192B0617689E9E7D ON contas');
        $this->addSql('ALTER TABLE contas DROP correntista_id');
    }
}
