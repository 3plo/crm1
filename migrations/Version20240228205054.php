<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240228205054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Complete card table and make relation for barcode';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE barcode
                ADD card_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'
        ');
        $this->addSql('
            ALTER TABLE barcode
                ADD CONSTRAINT FK_97AE02664ACC9A20
                FOREIGN KEY (card_id)
                REFERENCES card (id)
        ');
        $this->addSql('CREATE INDEX IDX_97AE02664ACC9A20 ON barcode (card_id)');
        $this->addSql('
            ALTER TABLE card
                ADD enabled TINYINT(1) NOT NULL,
                ADD type VARCHAR(255) NOT NULL,
                ADD max_usage INT DEFAULT NULL,
                ADD count_usage INT NOT NULL,
                ADD valid_from DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                ADD valid_till DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE barcode
                DROP FOREIGN KEY FK_97AE02664ACC9A20
        ');
        $this->addSql('DROP INDEX IDX_97AE02664ACC9A20 ON barcode');
        $this->addSql('
            ALTER TABLE barcode
                DROP card_id
        ');
        $this->addSql('
            ALTER TABLE card DROP enabled,
                DROP type,
                DROP max_usage,
                DROP count_usage,
                DROP valid_from,
                DROP valid_till,
                DROP created_at,
                DROP updated_at
        ');
    }
}
