<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240207174001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add price title';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE price ADD title VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE price DROP title');
    }
}
