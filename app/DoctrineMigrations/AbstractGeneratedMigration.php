<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Base class for generated migrations with "doctrine:migrations:diff"
 */
abstract class AbstractGeneratedMigration extends AbstractMigration
{
	public function up(Schema $schema)
	{
		$driverName = $this->connection->getDatabasePlatform()->getName();

		if ($driverName === 'sqlite') {
			$this->up_SQLite($schema);
			return;

		} elseif ($driverName === 'mysql') {
			$this->up_MySQL($schema);
			return;
		}
	}

	public function down(Schema $schema)
	{
		$driverName = $this->connection->getDatabasePlatform()->getName();

		if ($driverName === 'sqlite') {
			$this->down_SQLite($schema);
			return;

		} elseif ($driverName === 'mysql') {
			$this->down_MySQL($schema);
			return;
		}
	}

	abstract protected function up_MySQL(Schema $schema);
	abstract protected function up_SQLite(Schema $schema);
	abstract protected function down_MySQL(Schema $schema);
	abstract protected function down_SQLite(Schema $schema);
}
