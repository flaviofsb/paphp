<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230104151331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contas_copy (id INT AUTO_INCREMENT NOT NULL, contas_tipos_id INT NOT NULL, gerente_aprovacao_id INT DEFAULT NULL, correntista_id INT NOT NULL, agencia_id INT NOT NULL, saldo DOUBLE PRECISION DEFAULT NULL, data_hora_aprovacao DATETIME DEFAULT NULL, data_hora_criacao DATETIME NOT NULL, data_hora_cancelamento DATETIME DEFAULT NULL, status SMALLINT DEFAULT NULL, INDEX IDX_482CDFCE6AB1EA52 (contas_tipos_id), INDEX IDX_482CDFCEC7393753 (gerente_aprovacao_id), INDEX IDX_482CDFCE689E9E7D (correntista_id), INDEX IDX_482CDFCEA6F796BE (agencia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contas_copy ADD CONSTRAINT FK_482CDFCE6AB1EA52 FOREIGN KEY (contas_tipos_id) REFERENCES contas_tipos (id)');
        $this->addSql('ALTER TABLE contas_copy ADD CONSTRAINT FK_482CDFCEC7393753 FOREIGN KEY (gerente_aprovacao_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contas_copy ADD CONSTRAINT FK_482CDFCE689E9E7D FOREIGN KEY (correntista_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contas_copy ADD CONSTRAINT FK_482CDFCEA6F796BE FOREIGN KEY (agencia_id) REFERENCES agencias (id)');
        $this->addSql('ALTER TABLE transacoes DROP FOREIGN KEY FK_97CF7B5C332BCA77');
        $this->addSql('ALTER TABLE transacoes DROP FOREIGN KEY FK_97CF7B5C88825F03');
        $this->addSql('DROP INDEX IDX_97CF7B5C332BCA77 ON transacoes');
        $this->addSql('DROP INDEX IDX_97CF7B5C88825F03 ON transacoes');
        $this->addSql('ALTER TABLE transacoes ADD conta_origem VARCHAR(255) DEFAULT NULL, ADD conta_destino VARCHAR(255) DEFAULT NULL, DROP conta_origem_id, DROP conta_destino_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contas_copy DROP FOREIGN KEY FK_482CDFCE6AB1EA52');
        $this->addSql('ALTER TABLE contas_copy DROP FOREIGN KEY FK_482CDFCEC7393753');
        $this->addSql('ALTER TABLE contas_copy DROP FOREIGN KEY FK_482CDFCE689E9E7D');
        $this->addSql('ALTER TABLE contas_copy DROP FOREIGN KEY FK_482CDFCEA6F796BE');
        $this->addSql('DROP TABLE contas_copy');
        $this->addSql('ALTER TABLE transacoes ADD conta_origem_id INT DEFAULT NULL, ADD conta_destino_id INT DEFAULT NULL, DROP conta_origem, DROP conta_destino');
        $this->addSql('ALTER TABLE transacoes ADD CONSTRAINT FK_97CF7B5C332BCA77 FOREIGN KEY (conta_origem_id) REFERENCES contas (id)');
        $this->addSql('ALTER TABLE transacoes ADD CONSTRAINT FK_97CF7B5C88825F03 FOREIGN KEY (conta_destino_id) REFERENCES contas (id)');
        $this->addSql('CREATE INDEX IDX_97CF7B5C332BCA77 ON transacoes (conta_origem_id)');
        $this->addSql('CREATE INDEX IDX_97CF7B5C88825F03 ON transacoes (conta_destino_id)');
    }
}
