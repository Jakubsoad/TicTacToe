<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240816195821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Whole Game migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE board_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE game_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE score_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE board (id INT NOT NULL, game_id INT NOT NULL, piece VARCHAR(255) NOT NULL, x_position INT NOT NULL, y_position INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_58562B47E48FD905 ON board (game_id)');
        $this->addSql('COMMENT ON COLUMN board.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE game (id INT NOT NULL, current_turn VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN game.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE score (id INT NOT NULL, game_id INT NOT NULL, winner VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_32993751E48FD905 ON score (game_id)');
        $this->addSql('COMMENT ON COLUMN score.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE board ADD CONSTRAINT FK_58562B47E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_32993751E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE board_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE game_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE score_id_seq CASCADE');
        $this->addSql('ALTER TABLE board DROP CONSTRAINT FK_58562B47E48FD905');
        $this->addSql('ALTER TABLE score DROP CONSTRAINT FK_32993751E48FD905');
        $this->addSql('DROP TABLE board');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE score');
    }
}
