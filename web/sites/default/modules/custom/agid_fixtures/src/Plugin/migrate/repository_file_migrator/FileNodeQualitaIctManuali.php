<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodeQualitaIctManuali.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_qualita_ict_manuali_migrator",
 *   name = @Translation("File Repository Migrator - File Node Qualità ICT manuali"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "qualita_ict_manuali"
 *   }
 * )
 */
class FileNodeQualitaIctManuali extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return 'field_ict_manuali_documento';
  }

  /**
   * {@inheritdoc}
   */
  public function getFileArgumentsField() {
    return 'field_tags';
  }

}
