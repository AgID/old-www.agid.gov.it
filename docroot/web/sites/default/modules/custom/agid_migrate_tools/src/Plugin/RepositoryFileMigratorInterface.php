<?php

namespace Drupal\agid_migrate_tools\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\migrate\Row;
use Drupal\Core\Database\Connection;

/**
 * Defines an interface for Repository file migration plugins.
 */
interface RepositoryFileMigratorInterface extends PluginInspectionInterface {

  /**
   * Set file id.
   *
   * @param int $fid
   *   The file id.
   */
  public function setFid($fid);

  /**
   * Get file id.
   *
   * @return int
   *   The file id.
   */
  public function getFid();

  /**
   * Set migration row.
   *
   * @param \Drupal\migrate\Row $row
   *   The migrated row.
   */
  public function setRow(Row $row);

  /**
   * Get migration row.
   *
   * @return \Drupal\migrate\Row
   *   The migrated row.
   */
  public function getRow();

  /**
   * Get file title.
   *
   * @return string
   *   The file title.
   */
  public function getFileTitle();

  /**
   * Get file description.
   *
   * @return string
   *   The file description.
   */
  public function getFileDescription();

  /**
   * Get file description field used to fetch data from database.
   *
   * @return string
   *   The file description field name (without "field_data_" prefix).
   */
  public function getFileDescriptionField();

  /**
   * Get file arguments.
   *
   * @return array
   *   The file arguments array.
   */
  public function getFileArguments();

  /**
   * Get file arguments field used to fetch data from database.
   *
   * @return string
   *   The file arguments field name (without "field_data_" prefix).
   */
  public function getFileArgumentsField();

  /**
   * Get original file source.
   *
   * @return array
   *   An array containing the original file source term tid.
   */
  public function getFileSource();

  /**
   * Get file type.
   *
   * @return array
   *   An array containing the file type term tid.
   */
  public function getFileType();

  /**
   * Set database connection.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database object.
   */
  public function setDatabase(Connection $database);

  /**
   * Get database connection.
   *
   * @return \Drupal\Core\Database\Connection
   *   The database object.
   */
  public function getDatabase();

}
