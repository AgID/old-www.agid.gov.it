<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodeManualeGestioneProtocollo.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_manuale_gestione_protocollo_migrator",
 *   name = @Translation("File Repository Migrator - File Node Manuale Gestione Protocollo"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "manuale_gestione_protocollo"
 *   }
 * )
 */
class FileNodeManualeGestioneProtocollo extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return 'field_manuale_upload';
  }

}
