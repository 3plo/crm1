<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240226190526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create barcode';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE barcode (
                id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
                product_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\',
                barcode VARCHAR(255) NOT NULL,
                enabled TINYINT(1) NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                INDEX IDX_97AE02664584665A (product_id), PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('
            ALTER TABLE barcode
                ADD CONSTRAINT FK_97AE02664584665A
                FOREIGN KEY (product_id)
                REFERENCES product (id)
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE barcode
                DROP FOREIGN KEY FK_97AE02664584665A
        ');
        $this->addSql('DROP TABLE barcode');
    }
}
