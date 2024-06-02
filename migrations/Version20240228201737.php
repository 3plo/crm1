<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240228201737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create product and location relation';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE product_location (
                product_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
                location_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
                INDEX IDX_952EE35C4584665A (product_id),
                INDEX IDX_952EE35C64D218E (location_id),
                PRIMARY KEY(product_id, location_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('
            ALTER TABLE product_location
                ADD CONSTRAINT FK_952EE35C4584665A
                FOREIGN KEY (product_id)
                REFERENCES product (id) ON DELETE CASCADE
        ');
        $this->addSql('
            ALTER TABLE product_location
                ADD CONSTRAINT FK_952EE35C64D218E
                FOREIGN KEY (location_id)
                REFERENCES location (id) ON DELETE CASCADE
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE product_location
                DROP FOREIGN KEY FK_952EE35C4584665A
        ');
        $this->addSql('
            ALTER TABLE product_location
                DROP FOREIGN KEY FK_952EE35C64D218E
        ');
        $this->addSql('DROP TABLE product_location');
    }
}
