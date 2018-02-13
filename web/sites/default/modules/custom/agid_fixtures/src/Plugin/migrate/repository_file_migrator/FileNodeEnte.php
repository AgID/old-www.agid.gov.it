<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodeEnte.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_ente_migrator",
 *   name = @Translation("File Repository Migrator - File Node Ente"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "ente"
 *   }
 * )
 */
class FileNodeEnte extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return 'field_ente_manuale';
  }

  /**
   * {@inheritdoc}
   */
  public function getFileArgumentsField() {
    return 'field_ente_tags';
  }

}
