<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210731093919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'creation des tables necessaire a lauthentification';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE login_attempt (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_8C11C1BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE password_reset_token (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, token VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6B7BA4B6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, goal VARCHAR(255) DEFAULT NULL, avatar_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, country VARCHAR(2) DEFAULT \'FR\', banned_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, theme VARCHAR(255) DEFAULT NULL, mail_notification TINYINT(1) DEFAULT \'1\' NOT NULL, last_login_ip VARCHAR(255) DEFAULT NULL, last_login_at DATETIME DEFAULT NULL, invoice_info VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE login_attempt ADD CONSTRAINT FK_8C11C1BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE password_reset_token ADD CONSTRAINT FK_6B7BA4B6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE login_attempt DROP FOREIGN KEY FK_8C11C1BA76ED395');
        $this->addSql('ALTER TABLE password_reset_token DROP FOREIGN KEY FK_6B7BA4B6A76ED395');
        $this->addSql('DROP TABLE login_attempt');
        $this->addSql('DROP TABLE password_reset_token');
        $this->addSql('DROP TABLE `user`');
    }
}
