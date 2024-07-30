<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240730193139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE projects (
				id BIGINT NOT NULL,
				name VARCHAR(255) NOT NULL,
				description TEXT DEFAULT NULL,
				topics TEXT DEFAULT NULL,
				repository TEXT NOT NULL,
				homepage TEXT DEFAULT NULL,
				archived BOOLEAN DEFAULT false NOT NULL,
				last_pushed TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
				created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
				updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
				deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
				PRIMARY KEY(id)
			)'
        );
        $this->addSql('COMMENT ON COLUMN projects.last_pushed IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN projects.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN projects.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN projects.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN projects.topics IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('DROP TABLE projects');
    }
}
