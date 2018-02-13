<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodeBandiEsitiAvvisi.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_bandi_esiti_avvisi_migrator",
 *   name = @Translation("File Repository Migrator - File Node Bandi-Esiti-Avvisi"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "bandi_esiti_avvisi"
 *   }
 * )
 */
class FileNodeBandiEsitiAvvisi extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return 'field_bandi_testo_integrale';
  }

}
