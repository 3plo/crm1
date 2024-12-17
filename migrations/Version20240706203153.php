<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240706203153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user createdAt and updatedAt with default';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE user
                ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE user
                DROP created_at,
                DROP updated_at
        ');
    }
}
