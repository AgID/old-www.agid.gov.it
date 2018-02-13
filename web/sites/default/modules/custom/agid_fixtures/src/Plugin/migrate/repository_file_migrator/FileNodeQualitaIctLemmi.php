<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodeQualitaIctLemmi.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_qualita_ict_lemmi_migrator",
 *   name = @Translation("File Repository Migrator - File Node Qualità ICT lemmi"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "qualita_ict_lemmi"
 *   }
 * )
 */
class FileNodeQualitaIctLemmi extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return 'field_ict_lemmi_documento';
  }

  /**
   * {@inheritdoc}
   */
  public function getFileArgumentsField() {
    return 'field_tags';
  }

}
