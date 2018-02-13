<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodePagamentiAmministrazione.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_pagamenti_amministrazione_migrator",
 *   name = @Translation("File Repository Migrator - File Node Pagamenti amministrazione"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "pagamenti_amministrazione"
 *   }
 * )
 */
class FileNodePagamentiAmministrazione extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return 'field_pagamenti_documenti';
  }

}
