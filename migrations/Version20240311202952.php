<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240311202952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix scan log';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE scan_log
                CHANGE barcode_id barcode_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE scan_log
                CHANGE barcode_id barcode_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'
        ');
    }
}
