<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221010124642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE opinion (id INT AUTO_INCREMENT NOT NULL, response_id INT DEFAULT NULL, note INT NOT NULL, commentary VARCHAR(255) DEFAULT NULL, INDEX IDX_AB02B027FBF32840 (response_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE response (id INT AUTO_INCREMENT NOT NULL, response VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, opinion_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, postcode VARCHAR(5) NOT NULL, address VARCHAR(255) NOT NULL, phone INT DEFAULT NULL, images LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_EB95123F51885A6A (opinion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE opinion ADD CONSTRAINT FK_AB02B027FBF32840 FOREIGN KEY (response_id) REFERENCES response (id)');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F51885A6A FOREIGN KEY (opinion_id) REFERENCES opinion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B027FBF32840');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F51885A6A');
        $this->addSql('DROP TABLE opinion');
        $this->addSql('DROP TABLE response');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
