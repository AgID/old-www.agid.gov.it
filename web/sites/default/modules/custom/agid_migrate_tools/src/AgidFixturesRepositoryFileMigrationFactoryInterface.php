<?php

namespace Drupal\agid_migrate_tools;

use Drupal\Core\Database\Connection;
use Drupal\migrate\Row;

/**
 * Interface AgidFixturesRepositoryFileMigrationFactoryInterface.
 *
 * @package Drupal\advanced_rest
 */
interface AgidFixturesRepositoryFileMigrationFactoryInterface {

  /**
   * Retrieves the registered repository file migrator.
   *
   * @param \Drupal\migrate\Row $row
   *   The migration row.
   * @param \Drupal\Core\Database\Connection $database
   *   The Database connection.
   *
   * @return \Drupal\agid_migrate_tools\Plugin\RepositoryFileMigratorInterface
   *   The registered repository file migrator.
   */
  public function get(Row $row, Connection $database);

}
