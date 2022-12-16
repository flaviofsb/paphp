<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214204945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agencias ADD gerente_id INT NOT NULL');
        $this->addSql('ALTER TABLE agencias ADD CONSTRAINT FK_923688C05AEA750D FOREIGN KEY (gerente_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_923688C05AEA750D ON agencias (gerente_id)');
        $this->addSql('ALTER TABLE user ADD agencias_correntistas_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64960DED760 FOREIGN KEY (agencias_correntistas_id) REFERENCES agencias (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64960DED760 ON user (agencias_correntistas_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agencias DROP FOREIGN KEY FK_923688C05AEA750D');
        $this->addSql('DROP INDEX UNIQ_923688C05AEA750D ON agencias');
        $this->addSql('ALTER TABLE agencias DROP gerente_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64960DED760');
        $this->addSql('DROP INDEX IDX_8D93D64960DED760 ON user');
        $this->addSql('ALTER TABLE user DROP agencias_correntistas_id');
    }
}
