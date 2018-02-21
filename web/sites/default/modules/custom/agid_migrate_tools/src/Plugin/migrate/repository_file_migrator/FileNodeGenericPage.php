<?php

namespace Drupal\agid_migrate_tools\Plugin\migrate\repository_file_migrator;

use Drupal\agid_migrate_tools\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodeGenericPage.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_generic_page_migrator",
 *   name = @Translation("File Repository Migrator - File Node Generic page"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "pagina_generica_alberatura"
 *   }
 * )
 */
class FileNodeGenericPage extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return [
      'field_circolari_deliberazioni',
      'field_leggi_decreti_direttive',
      'field_documentazione_allegata',
      'field_regole_tecniche',
      'field_linee_guida',
      'field_documenti_indirizzo',
      'field_presentazioni',
      'field_protocolli_intesa',
      'field_accordi_istituzionali',
      'field_programmi_quadro'
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFileArgumentsField() {
    return 'field_tags';
  }

}
