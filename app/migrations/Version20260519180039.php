<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260519180039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fazenda_veterinario (fazenda_id INT NOT NULL, veterinario_id INT NOT NULL, PRIMARY KEY (fazenda_id, veterinario_id))');
        $this->addSql('CREATE INDEX IDX_4D394109D4A3545F ON fazenda_veterinario (fazenda_id)');
        $this->addSql('CREATE INDEX IDX_4D3941091454BD8B ON fazenda_veterinario (veterinario_id)');
        $this->addSql('ALTER TABLE fazenda_veterinario ADD CONSTRAINT FK_4D394109D4A3545F FOREIGN KEY (fazenda_id) REFERENCES fazenda (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fazenda_veterinario ADD CONSTRAINT FK_4D3941091454BD8B FOREIGN KEY (veterinario_id) REFERENCES veterinario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gado ADD fazenda_id INT NOT NULL');
        $this->addSql('ALTER TABLE gado ADD CONSTRAINT FK_123C63DBD4A3545F FOREIGN KEY (fazenda_id) REFERENCES fazenda (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_123C63DBD4A3545F ON gado (fazenda_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fazenda_veterinario DROP CONSTRAINT FK_4D394109D4A3545F');
        $this->addSql('ALTER TABLE fazenda_veterinario DROP CONSTRAINT FK_4D3941091454BD8B');
        $this->addSql('DROP TABLE fazenda_veterinario');
        $this->addSql('ALTER TABLE gado DROP CONSTRAINT FK_123C63DBD4A3545F');
        $this->addSql('DROP INDEX IDX_123C63DBD4A3545F');
        $this->addSql('ALTER TABLE gado DROP fazenda_id');
    }
}
