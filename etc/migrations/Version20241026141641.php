<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241026141641 extends AbstractMigration
{
    #[\Override]
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE DOMAIN project_id AS BIGINT');
        $this->addSql('CREATE DOMAIN project_repository_url AS TEXT');
        $this->addSql('CREATE DOMAIN url AS TEXT');
        $this->addSql(
            'CREATE TABLE projects (
				id project_id NOT NULL,
				name VARCHAR(255) NOT NULL,
				description TEXT DEFAULT NULL,
				topics TEXT DEFAULT NULL,
				repository project_repository_url NOT NULL,
				homepage url DEFAULT NULL,
				archived BOOLEAN DEFAULT false NOT NULL,
				last_pushed_at TIMESTAMP(0) WITH TIME ZONE NOT NULL,
				created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL,
				updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL,
				deleted_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL,
				created_at_timestamp BIGINT NOT NULL,
				PRIMARY KEY(id, created_at_timestamp)
			)'
        );

        $this->addSql('CREATE INDEX id_idx ON projects (id)');
        $this->addSql('CREATE UNIQUE INDEX unique_id_deleted_at_idx ON projects (id, deleted_at)');

        $this->addSql('COMMENT ON COLUMN projects.id IS \'(DC2Type:project_id)\'');
        $this->addSql('COMMENT ON COLUMN projects.repository IS \'(DC2Type:project_repository_url)\'');
        $this->addSql('COMMENT ON COLUMN projects.homepage IS \'(DC2Type:url)\'');
        $this->addSql('COMMENT ON COLUMN projects.topics IS \'(DC2Type:simple_array)\'');
        $this->addSql('COMMENT ON COLUMN projects.last_pushed_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN projects.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN projects.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN projects.deleted_at IS \'(DC2Type:datetimetz_immutable)\'');
    }

    #[\Override]
    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('DROP TABLE projects');
    }
}
