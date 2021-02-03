<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210106174432 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE soumission ADD apprenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE soumission ADD CONSTRAINT FK_9495AA2EC5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('CREATE INDEX IDX_9495AA2EC5697D6D ON soumission (apprenant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE soumission DROP FOREIGN KEY FK_9495AA2EC5697D6D');
        $this->addSql('DROP INDEX IDX_9495AA2EC5697D6D ON soumission');
        $this->addSql('ALTER TABLE soumission DROP apprenant_id');
    }
}
