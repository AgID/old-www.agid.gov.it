<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodeGenericPageNewsOffices.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_generic_page_news_offices_migrator",
 *   name = @Translation("File Repository Migrator - File Node Generic page, news, offices"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "pagina_generica_alberatura",
 *    "pagina_novita",
 *    "ufficio_stampa"
 *   }
 * )
 */
class FileNodeGenericPageNewsOffices extends RepositoryFileMigratorBase {

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
