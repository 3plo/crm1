<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250211213801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add relation from card to price';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE card
                ADD price_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'
        ');
        $this->addSql('
            ALTER TABLE card
                ADD CONSTRAINT FK_161498D3D614C7E7
                FOREIGN KEY (price_id)
                REFERENCES price (id)
        ');
        $this->addSql('CREATE INDEX IDX_161498D3D614C7E7 ON card (price_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE card
                DROP FOREIGN KEY FK_161498D3D614C7E7
        ');
        $this->addSql('DROP INDEX IDX_161498D3D614C7E7 ON card');
        $this->addSql('
            ALTER TABLE card
                DROP price_id
        ');
    }
}
