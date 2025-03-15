<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250308104307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add is_generated flag to barcode';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE barcode
                ADD is_generated TINYINT(1) DEFAULT 1 NOT NULL
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE barcode
                DROP is_generated
        ');
    }
}
