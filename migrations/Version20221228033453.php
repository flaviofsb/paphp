<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221228033453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contas ADD agencia_id INT NOT NULL');
        $this->addSql('ALTER TABLE contas ADD CONSTRAINT FK_192B0617A6F796BE FOREIGN KEY (agencia_id) REFERENCES agencias (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_192B0617A6F796BE ON contas (agencia_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contas DROP FOREIGN KEY FK_192B0617A6F796BE');
        $this->addSql('DROP INDEX UNIQ_192B0617A6F796BE ON contas');
        $this->addSql('ALTER TABLE contas DROP agencia_id');
    }
}
