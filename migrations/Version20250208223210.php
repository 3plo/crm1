<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250208223210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create created by in card';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE card
                ADD created_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'
        ');
        $this->addSql('
            ALTER TABLE card
                ADD CONSTRAINT FK_161498D3B03A8386
                FOREIGN KEY (created_by_id)
                REFERENCES user (id)
        ');
        $this->addSql('CREATE INDEX IDX_161498D3B03A8386 ON card (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE card
                DROP FOREIGN KEY FK_161498D3B03A8386');
        $this->addSql('DROP INDEX IDX_161498D3B03A8386 ON card');
        $this->addSql('
            ALTER TABLE card
                DROP created_by_id
        ');
    }
}
