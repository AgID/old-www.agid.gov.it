<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodeIniziative.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_iniziative",
 *   name = @Translation("File Repository Migrator - File Node Iniziative"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "iniziative"
 *   }
 * )
 */
class FileNodeIniziative extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return [
      'field_iniziative_documento',
      'field_iniziative_allegato_info',
      'field_iniziative_modulistica',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFileArgumentsField() {
    return 'field_tags';
  }

}
