<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201230130630 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video_conference ADD cours_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE video_conference ADD CONSTRAINT FK_11B22B7E7ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('CREATE INDEX IDX_11B22B7E7ECF78B0 ON video_conference (cours_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video_conference DROP FOREIGN KEY FK_11B22B7E7ECF78B0');
        $this->addSql('DROP INDEX IDX_11B22B7E7ECF78B0 ON video_conference');
        $this->addSql('ALTER TABLE video_conference DROP cours_id');
    }
}
