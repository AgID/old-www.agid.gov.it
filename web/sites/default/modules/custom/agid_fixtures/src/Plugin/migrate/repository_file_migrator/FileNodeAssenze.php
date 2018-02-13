<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodeAssenze.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_assenze_migrator",
 *   name = @Translation("File Repository Migrator - File Node Assenze"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "assenze"
 *   }
 * )
 */
class FileNodeAssenze extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return 'field_assenze_file_upload';
  }

}
