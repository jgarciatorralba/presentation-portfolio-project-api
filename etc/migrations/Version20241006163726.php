<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241006163726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE DOMAIN project_id AS BIGINT');
        $this->addSql('CREATE DOMAIN project_repository AS TEXT');
        $this->addSql('CREATE DOMAIN url AS TEXT');
        $this->addSql(
            'CREATE TABLE projects (
				id project_id NOT NULL,
				name VARCHAR(255) NOT NULL,
				description TEXT DEFAULT NULL,
				topics TEXT DEFAULT NULL,
				repository project_repository NOT NULL,
				homepage url DEFAULT NULL,
				archived BOOLEAN DEFAULT false NOT NULL,
				last_pushed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
				created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
				updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
				deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
				PRIMARY KEY(id)
			)'
        );
        $this->addSql('COMMENT ON COLUMN projects.id IS \'(DC2Type:project_id)\'');
        $this->addSql('COMMENT ON COLUMN projects.repository IS \'(DC2Type:project_repository)\'');
        $this->addSql('COMMENT ON COLUMN projects.homepage IS \'(DC2Type:url)\'');
        $this->addSql('COMMENT ON COLUMN projects.topics IS \'(DC2Type:simple_array)\'');
        $this->addSql('COMMENT ON COLUMN projects.last_pushed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN projects.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN projects.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN projects.deleted_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('DROP TABLE projects');
    }
}
