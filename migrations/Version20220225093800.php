<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220225093800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_review');
        $this->addSql('ALTER TABLE review ADD user_post_id INT DEFAULT NULL, ADD user_receiver_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C613841D26 FOREIGN KEY (user_post_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C664482423 FOREIGN KEY (user_receiver_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_794381C613841D26 ON review (user_post_id)');
        $this->addSql('CREATE INDEX IDX_794381C664482423 ON review (user_receiver_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_review (user_id INT NOT NULL, review_id INT NOT NULL, INDEX IDX_1C119AFBA76ED395 (user_id), INDEX IDX_1C119AFB3E2E969B (review_id), PRIMARY KEY(user_id, review_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_review ADD CONSTRAINT FK_1C119AFB3E2E969B FOREIGN KEY (review_id) REFERENCES review (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_review ADD CONSTRAINT FK_1C119AFBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C613841D26');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C664482423');
        $this->addSql('DROP INDEX IDX_794381C613841D26 ON review');
        $this->addSql('DROP INDEX IDX_794381C664482423 ON review');
        $this->addSql('ALTER TABLE review DROP user_post_id, DROP user_receiver_id');
    }
}
