<?php

namespace Drupal\agid_migrate_tools\Plugin\migrate\repository_file_migrator;

use Drupal\agid_migrate_tools\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodeNews.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_news_migrator",
 *   name = @Translation("File Repository Migrator - File Node news"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "pagina_novita"
 *   }
 * )
 */
class FileNodeNews extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return 'field_documentazione_allegata';
  }

  /**
   * {@inheritdoc}
   */
  public function getFileArgumentsField() {
    return 'field_tags';
  }

}
