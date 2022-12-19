<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221219152817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contas CHANGE saldo saldo DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE transacoes CHANGE conta_origem_id conta_origem_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contas CHANGE saldo saldo DOUBLE PRECISION DEFAULT \'0\'');
        $this->addSql('ALTER TABLE transacoes CHANGE conta_origem_id conta_origem_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL');
    }
}
