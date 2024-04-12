<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240412103045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD title VARCHAR(60) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD date_update DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE response ADD body_response VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD date_update DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE thread ADD status VARCHAR(20) NOT NULL, ADD title VARCHAR(100) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD date_update DATE DEFAULT NULL, ADD body VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD date_update DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE votes ADD vote TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP title, DROP created_at, DROP date_update');
        $this->addSql('ALTER TABLE response DROP body_response, DROP created_at, DROP date_update');
        $this->addSql('ALTER TABLE thread DROP status, DROP title, DROP description, DROP created_at, DROP date_update, DROP body');
        $this->addSql('ALTER TABLE user DROP username, DROP created_at, DROP date_update');
        $this->addSql('ALTER TABLE votes DROP vote');
    }
}
