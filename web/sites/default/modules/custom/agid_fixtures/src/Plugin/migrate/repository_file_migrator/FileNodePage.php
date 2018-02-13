<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodePage.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_page_migrator",
 *   name = @Translation("File Repository Migrator - File Node Page"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "page"
 *   }
 * )
 */
class FileNodePage extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return 'field_documentazione';
  }

  /**
   * {@inheritdoc}
   */
  public function getFileArgumentsField() {
    return 'field_tags';
  }

}
