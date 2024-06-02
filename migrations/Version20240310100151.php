<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240310100151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create scan log and expand product and barcode';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE scan_log (
                id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
                barcode_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
                status TINYINT(1) NOT NULL, barcode_string VARCHAR(255) NOT NULL,
                message VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                INDEX IDX_FB19A49229439E58 (barcode_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('
            ALTER TABLE scan_log
                ADD CONSTRAINT FK_FB19A49229439E58
                    FOREIGN KEY (barcode_id)
                    REFERENCES barcode (id)
        ');
        $this->addSql('CREATE INDEX IDX_97AE02664ACC9A20 ON barcode (card_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97AE026697AE0266 ON barcode (barcode)');
        $this->addSql('
            ALTER TABLE card
                ADD product_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\',
                ADD last_usage DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'
        ');
        $this->addSql('
            ALTER TABLE card
                ADD CONSTRAINT FK_161498D34584665A
                    FOREIGN KEY (product_id)
                    REFERENCES product (id)
        ');
        $this->addSql('CREATE INDEX IDX_161498D34584665A ON card (product_id)');
        $this->addSql('
            ALTER TABLE product
                ADD type VARCHAR(255) NOT NULL,
                ADD count_usage INT UNSIGNED NOT NULL
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE scan_log
                DROP FOREIGN KEY FK_FB19A49229439E58
        ');
        $this->addSql('DROP TABLE scan_log');
        $this->addSql('DROP INDEX IDX_97AE02664ACC9A20 ON barcode');
        $this->addSql('DROP INDEX UNIQ_97AE026697AE0266 ON barcode');
        $this->addSql('
            ALTER TABLE card
                DROP FOREIGN KEY FK_161498D34584665A
        ');
        $this->addSql('DROP INDEX IDX_161498D34584665A ON card');
        $this->addSql('
            ALTER TABLE card
                DROP product_id,
                DROP last_usage
            ');
        $this->addSql('
            ALTER TABLE product DROP type,
                DROP count_usage
        ');
    }
}
