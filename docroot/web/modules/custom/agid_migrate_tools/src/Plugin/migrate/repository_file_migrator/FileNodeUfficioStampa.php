<?php

namespace Drupal\agid_migrate_tools\Plugin\migrate\repository_file_migrator;

use Drupal\agid_migrate_tools\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodeUfficioStampa.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_ufficio_stampa_migrator",
 *   name = @Translation("File Repository Migrator - File Node Ufficio Stampa"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "ufficio_stampa"
 *   }
 * )
 */
class FileNodeUfficioStampa extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return 'field_documentazione_allegata';
  }

}
