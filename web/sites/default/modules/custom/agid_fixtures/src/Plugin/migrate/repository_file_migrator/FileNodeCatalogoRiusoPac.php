<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodeCatalogoRiusoPac.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_catalogo_pac_migrator",
 *   name = @Translation("File Repository Migrator - File Node Catalogo riuso PAC"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "catalogo_pac"
 *   }
 * )
 */
class FileNodeCatalogoRiusoPac extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return 'field_catalogo_scheda';
  }

}
