<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240313200422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add location to scan log';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE scan_log
                ADD location_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'
        ');
        $this->addSql('
            ALTER TABLE scan_log
                ADD CONSTRAINT FK_FB19A49264D218E
                FOREIGN KEY (location_id)
                REFERENCES location (id)
        ');
        $this->addSql('CREATE INDEX IDX_FB19A49264D218E ON scan_log (location_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE scan_log
                DROP FOREIGN KEY FK_FB19A49264D218E
        ');
        $this->addSql('DROP INDEX IDX_FB19A49264D218E ON scan_log');
        $this->addSql('
            ALTER TABLE scan_log
                DROP location_id
        ');
    }
}
