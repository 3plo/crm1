<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240408201922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Expand user settings';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE user
                ADD first_name VARCHAR(255) NOT NULL,
                ADD last_name VARCHAR(255) NOT NULL,
                ADD access_list TEXT NOT NULL COMMENT \'(DC2Type:simple_array)\',
                ADD location_access_list TEXT NOT NULL COMMENT \'(DC2Type:simple_array)\',
                ADD enabled TINYINT(1) NOT NULL,
                CHANGE email email VARCHAR(255) NOT NULL,
                CHANGE roles roles TEXT NOT NULL COMMENT \'(DC2Type:simple_array)\'
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE user
                DROP first_name,
                DROP last_name,
                DROP access_list,
                DROP location_access_list,
                DROP enabled, CHANGE email email VARCHAR(180) NOT NULL,
                CHANGE roles roles JSON NOT NULL
        ');
    }
}
