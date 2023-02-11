<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230211080610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE call_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE call_status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE friend_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE call (id INT NOT NULL, status_id INT NOT NULL, friend_id INT DEFAULT NULL, date_create TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CC8E2F3E6BF700BD ON call (status_id)');
        $this->addSql('CREATE INDEX IDX_CC8E2F3E6A5458E8 ON call (friend_id)');
        $this->addSql('COMMENT ON COLUMN call.date_create IS \'Дата/время звонка\'');
        $this->addSql('CREATE TABLE call_status (id INT NOT NULL, value VARCHAR(20) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN call_status.value IS \'Значение\'');
        $this->addSql('COMMENT ON COLUMN call_status.description IS \'Описание\'');
        $this->addSql('CREATE TABLE friend (id INT NOT NULL, user_id INT DEFAULT NULL, external_id BIGINT NOT NULL, first_name VARCHAR(80) NOT NULL, last_name VARCHAR(80) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_55EEAC61A76ED395 ON friend (user_id)');
        $this->addSql('CREATE INDEX friend_externalId ON friend (external_id)');
        $this->addSql('COMMENT ON COLUMN friend.external_id IS \'Внешний идентификатор друга\'');
        $this->addSql('COMMENT ON COLUMN friend.first_name IS \'Имя друга\'');
        $this->addSql('COMMENT ON COLUMN friend.last_name IS \'Фамилия друга\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, session_id VARCHAR(30) NOT NULL, external_id BIGINT NOT NULL, access_token TEXT NOT NULL, date_create TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX user_sessionId ON "user" (session_id)');
        $this->addSql('CREATE INDEX user_externalId ON "user" (external_id)');
        $this->addSql('COMMENT ON COLUMN "user".session_id IS \'Идентификатор сессии\'');
        $this->addSql('COMMENT ON COLUMN "user".external_id IS \'Внешний идентификатор пользователя\'');
        $this->addSql('COMMENT ON COLUMN "user".access_token IS \'Специальный ключ доступа\'');
        $this->addSql('COMMENT ON COLUMN "user".date_create IS \'Дата/время добавления записи\'');
        $this->addSql('ALTER TABLE call ADD CONSTRAINT FK_CC8E2F3E6BF700BD FOREIGN KEY (status_id) REFERENCES call_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE call ADD CONSTRAINT FK_CC8E2F3E6A5458E8 FOREIGN KEY (friend_id) REFERENCES friend (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC61A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE call_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE call_status_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE friend_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE call DROP CONSTRAINT FK_CC8E2F3E6BF700BD');
        $this->addSql('ALTER TABLE call DROP CONSTRAINT FK_CC8E2F3E6A5458E8');
        $this->addSql('ALTER TABLE friend DROP CONSTRAINT FK_55EEAC61A76ED395');
        $this->addSql('DROP TABLE call');
        $this->addSql('DROP TABLE call_status');
        $this->addSql('DROP TABLE friend');
        $this->addSql('DROP TABLE "user"');
    }
}
