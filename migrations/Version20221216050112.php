<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221216050112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agencias ADD numero VARCHAR(255) NOT NULL, ADD telefone VARCHAR(255) NOT NULL, ADD logradouro VARCHAR(255) NOT NULL, ADD complemento VARCHAR(255) DEFAULT NULL, ADD numero_endereco VARCHAR(255) DEFAULT NULL, ADD cep VARCHAR(255) DEFAULT NULL, ADD bairro VARCHAR(255) NOT NULL, ADD cidade VARCHAR(255) NOT NULL, ADD uf VARCHAR(255) NOT NULL, CHANGE agencia nome VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE contas ADD contas_tipos_id INT NOT NULL, ADD gerente_aprovacao_id INT DEFAULT NULL, ADD data_hora_aprovacao DATETIME DEFAULT NULL, ADD data_hora_criacao DATETIME NOT NULL, ADD data_hora_cancelamento DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE contas ADD CONSTRAINT FK_192B06176AB1EA52 FOREIGN KEY (contas_tipos_id) REFERENCES contas_tipos (id)');
        $this->addSql('ALTER TABLE contas ADD CONSTRAINT FK_192B0617C7393753 FOREIGN KEY (gerente_aprovacao_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_192B06176AB1EA52 ON contas (contas_tipos_id)');
        $this->addSql('CREATE INDEX IDX_192B0617C7393753 ON contas (gerente_aprovacao_id)');
        $this->addSql('ALTER TABLE transacoes ADD conta_origem_id INT NOT NULL, ADD conta_destino_id INT DEFAULT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE transacoes ADD CONSTRAINT FK_97CF7B5C332BCA77 FOREIGN KEY (conta_origem_id) REFERENCES contas (id)');
        $this->addSql('ALTER TABLE transacoes ADD CONSTRAINT FK_97CF7B5C88825F03 FOREIGN KEY (conta_destino_id) REFERENCES contas (id)');
        $this->addSql('ALTER TABLE transacoes ADD CONSTRAINT FK_97CF7B5CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_97CF7B5C332BCA77 ON transacoes (conta_origem_id)');
        $this->addSql('CREATE INDEX IDX_97CF7B5C88825F03 ON transacoes (conta_destino_id)');
        $this->addSql('CREATE INDEX IDX_97CF7B5CA76ED395 ON transacoes (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64960DED760');
        $this->addSql('DROP INDEX IDX_8D93D64960DED760 ON user');
        $this->addSql('ALTER TABLE user ADD data_hora_criacao DATETIME NOT NULL, ADD data_hora_cancelamento DATETIME DEFAULT NULL, CHANGE agencias_correntistas_id agencia_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A6F796BE FOREIGN KEY (agencia_id) REFERENCES agencias (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649A6F796BE ON user (agencia_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agencias ADD agencia VARCHAR(255) NOT NULL, DROP nome, DROP numero, DROP telefone, DROP logradouro, DROP complemento, DROP numero_endereco, DROP cep, DROP bairro, DROP cidade, DROP uf');
        $this->addSql('ALTER TABLE contas DROP FOREIGN KEY FK_192B06176AB1EA52');
        $this->addSql('ALTER TABLE contas DROP FOREIGN KEY FK_192B0617C7393753');
        $this->addSql('DROP INDEX IDX_192B06176AB1EA52 ON contas');
        $this->addSql('DROP INDEX IDX_192B0617C7393753 ON contas');
        $this->addSql('ALTER TABLE contas DROP contas_tipos_id, DROP gerente_aprovacao_id, DROP data_hora_aprovacao, DROP data_hora_criacao, DROP data_hora_cancelamento');
        $this->addSql('ALTER TABLE transacoes DROP FOREIGN KEY FK_97CF7B5C332BCA77');
        $this->addSql('ALTER TABLE transacoes DROP FOREIGN KEY FK_97CF7B5C88825F03');
        $this->addSql('ALTER TABLE transacoes DROP FOREIGN KEY FK_97CF7B5CA76ED395');
        $this->addSql('DROP INDEX IDX_97CF7B5C332BCA77 ON transacoes');
        $this->addSql('DROP INDEX IDX_97CF7B5C88825F03 ON transacoes');
        $this->addSql('DROP INDEX IDX_97CF7B5CA76ED395 ON transacoes');
        $this->addSql('ALTER TABLE transacoes DROP conta_origem_id, DROP conta_destino_id, DROP user_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A6F796BE');
        $this->addSql('DROP INDEX IDX_8D93D649A6F796BE ON user');
        $this->addSql('ALTER TABLE user DROP data_hora_criacao, DROP data_hora_cancelamento, CHANGE agencia_id agencias_correntistas_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64960DED760 FOREIGN KEY (agencias_correntistas_id) REFERENCES agencias (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64960DED760 ON user (agencias_correntistas_id)');
    }
}
