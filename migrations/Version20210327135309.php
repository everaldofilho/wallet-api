<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210327135309 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction (id UUID NOT NULL, user_id UUID DEFAULT NULL, transaction_status_id INT DEFAULT NULL, transaction_type_id INT DEFAULT NULL, transaction_category_id INT DEFAULT NULL, transaction_transfer_id UUID DEFAULT NULL, description VARCHAR(100) NOT NULL, value NUMERIC(10, 2) NOT NULL, notification BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_723705D1A76ED395 ON transaction (user_id)');
        $this->addSql('CREATE INDEX IDX_723705D128D09BFE ON transaction (transaction_status_id)');
        $this->addSql('CREATE INDEX IDX_723705D1B3E6B071 ON transaction (transaction_type_id)');
        $this->addSql('CREATE INDEX IDX_723705D1AECF88CF ON transaction (transaction_category_id)');
        $this->addSql('CREATE INDEX IDX_723705D1EFF95D82 ON transaction (transaction_transfer_id)');
        $this->addSql('COMMENT ON COLUMN transaction.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN transaction.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN transaction.transaction_transfer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE transaction_category (id INT NOT NULL, description VARCHAR(45) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE transaction_status (id INT NOT NULL, description VARCHAR(45) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE transaction_transfer (id UUID NOT NULL, from_user_id UUID DEFAULT NULL, to_user_id UUID DEFAULT NULL, value NUMERIC(10, 2) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, Transactio_status_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E468AA82130303A ON transaction_transfer (from_user_id)');
        $this->addSql('CREATE INDEX IDX_E468AA829F6EE60 ON transaction_transfer (to_user_id)');
        $this->addSql('CREATE INDEX IDX_E468AA89688D5B8 ON transaction_transfer (Transactio_status_id)');
        $this->addSql('COMMENT ON COLUMN transaction_transfer.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN transaction_transfer.from_user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN transaction_transfer.to_user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE transaction_type (id INT NOT NULL, description VARCHAR(45) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, user_type_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, email VARCHAR(50) NOT NULL, document VARCHAR(14) NOT NULL, password VARCHAR(150) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D8698A76 ON "user" (document)');
        $this->addSql('CREATE INDEX IDX_8D93D6499D419299 ON "user" (user_type_id)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE user_type (id INT NOT NULL, description VARCHAR(45) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE wallet (id UUID NOT NULL, user_id UUID DEFAULT NULL, last_transaction_id UUID DEFAULT NULL, balance NUMERIC(10, 2) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7C68921FA76ED395 ON wallet (user_id)');
        $this->addSql('CREATE INDEX IDX_7C68921F41C7C28B ON wallet (last_transaction_id)');
        $this->addSql('COMMENT ON COLUMN wallet.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN wallet.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN wallet.last_transaction_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D128D09BFE FOREIGN KEY (transaction_status_id) REFERENCES transaction_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1B3E6B071 FOREIGN KEY (transaction_type_id) REFERENCES transaction_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1AECF88CF FOREIGN KEY (transaction_category_id) REFERENCES transaction_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1EFF95D82 FOREIGN KEY (transaction_transfer_id) REFERENCES transaction_transfer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction_transfer ADD CONSTRAINT FK_E468AA82130303A FOREIGN KEY (from_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction_transfer ADD CONSTRAINT FK_E468AA829F6EE60 FOREIGN KEY (to_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction_transfer ADD CONSTRAINT FK_E468AA89688D5B8 FOREIGN KEY (Transactio_status_id) REFERENCES transaction_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6499D419299 FOREIGN KEY (user_type_id) REFERENCES user_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F41C7C28B FOREIGN KEY (last_transaction_id) REFERENCES transaction (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE wallet DROP CONSTRAINT FK_7C68921F41C7C28B');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1AECF88CF');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D128D09BFE');
        $this->addSql('ALTER TABLE transaction_transfer DROP CONSTRAINT FK_E468AA89688D5B8');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1EFF95D82');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1B3E6B071');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1A76ED395');
        $this->addSql('ALTER TABLE transaction_transfer DROP CONSTRAINT FK_E468AA82130303A');
        $this->addSql('ALTER TABLE transaction_transfer DROP CONSTRAINT FK_E468AA829F6EE60');
        $this->addSql('ALTER TABLE wallet DROP CONSTRAINT FK_7C68921FA76ED395');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6499D419299');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE transaction_category');
        $this->addSql('DROP TABLE transaction_status');
        $this->addSql('DROP TABLE transaction_transfer');
        $this->addSql('DROP TABLE transaction_type');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_type');
        $this->addSql('DROP TABLE wallet');
    }
}
