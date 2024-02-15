<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240209221726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE location (
                id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
                enabled TINYINT(1) NOT NULL,
                title VARCHAR(255) NOT NULL,
                description LONGTEXT NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE regular_scheduler (
                id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
                location_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\',
                date_from DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                date_till DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                day_number SMALLINT UNSIGNED NOT NULL,
                time_from TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\',
                time_till TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\',
                enabled TINYINT(1) NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                INDEX IDX_E456E5CA64D218E (location_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE special_scheduler (
                id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
                location_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\',
                date_from DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                date_till DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                time_from DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                time_till DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                INDEX IDX_28D7579464D218E (location_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE vacation_scheduler (
                id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
                location_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\',
                title VARCHAR(255) NOT NULL,
                date_from DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                date_till DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                day_number SMALLINT UNSIGNED NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                INDEX IDX_75EB5B6F64D218E (location_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('
            ALTER TABLE regular_scheduler
                ADD CONSTRAINT FK_E456E5CA64D218E FOREIGN KEY (location_id) REFERENCES location (id)
        ');
        $this->addSql('
            ALTER TABLE special_scheduler
                ADD CONSTRAINT FK_28D7579464D218E FOREIGN KEY (location_id) REFERENCES location (id)
        ');
        $this->addSql('
            ALTER TABLE vacation_scheduler
                ADD CONSTRAINT FK_75EB5B6F64D218E FOREIGN KEY (location_id) REFERENCES location (id)
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE regular_scheduler
                DROP FOREIGN KEY FK_E456E5CA64D218E
        ');
        $this->addSql('
            ALTER TABLE special_scheduler
                DROP FOREIGN KEY FK_28D7579464D218E
        ');
        $this->addSql('
            ALTER TABLE vacation_scheduler
                DROP FOREIGN KEY FK_75EB5B6F64D218E
        ');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE regular_scheduler');
        $this->addSql('DROP TABLE special_scheduler');
        $this->addSql('DROP TABLE vacation_scheduler');
    }
}
