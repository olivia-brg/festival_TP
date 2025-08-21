<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250820135451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE music_genre (id INT AUTO_INCREMENT NOT NULL, genre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE music_genre_artist (music_genre_id INT NOT NULL, artist_id INT NOT NULL, INDEX IDX_6EFA971085858F39 (music_genre_id), INDEX IDX_6EFA9710B7970CF8 (artist_id), PRIMARY KEY(music_genre_id, artist_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE music_genre_music (music_genre_id INT NOT NULL, music_id INT NOT NULL, INDEX IDX_DE24549C85858F39 (music_genre_id), INDEX IDX_DE24549C399BBB13 (music_id), PRIMARY KEY(music_genre_id, music_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE music_genre_artist ADD CONSTRAINT FK_6EFA971085858F39 FOREIGN KEY (music_genre_id) REFERENCES music_genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE music_genre_artist ADD CONSTRAINT FK_6EFA9710B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE music_genre_music ADD CONSTRAINT FK_DE24549C85858F39 FOREIGN KEY (music_genre_id) REFERENCES music_genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE music_genre_music ADD CONSTRAINT FK_DE24549C399BBB13 FOREIGN KEY (music_id) REFERENCES music (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE music_genre_artist DROP FOREIGN KEY FK_6EFA971085858F39');
        $this->addSql('ALTER TABLE music_genre_artist DROP FOREIGN KEY FK_6EFA9710B7970CF8');
        $this->addSql('ALTER TABLE music_genre_music DROP FOREIGN KEY FK_DE24549C85858F39');
        $this->addSql('ALTER TABLE music_genre_music DROP FOREIGN KEY FK_DE24549C399BBB13');
        $this->addSql('DROP TABLE music_genre');
        $this->addSql('DROP TABLE music_genre_artist');
        $this->addSql('DROP TABLE music_genre_music');
    }
}
