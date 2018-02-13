<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodeAvvisi.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_avvisi_migrator",
 *   name = @Translation("File Repository Migrator - File Node Avvisi"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "avvisi"
 *   }
 * )
 */
class FileNodeAvvisi extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return 'field_avvisi_upload';
  }

}
