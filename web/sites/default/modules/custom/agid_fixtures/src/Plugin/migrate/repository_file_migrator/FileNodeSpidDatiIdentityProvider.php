<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodeSpidDatiIdentityProvider.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_spid_dati_identity_provider",
 *   name = @Translation("File Repository Migrator - File Node SPID - Dati Identity Provider"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "spid_dati_identity_provider"
 *   }
 * )
 */
class FileNodeSpidDatiIdentityProvider extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return [
      'field_logo_idp',
      'field_logo_servizio_spid',
      'field_determina_accreditamento',
      'field_convenzione',
      'field_determina',
      'field_manuale_operativo',
      'field_manuale_utente',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFileDescription() {
    $field = $this->getFileDescriptionField();
    if (is_array($field)) {
      $field = $this->extractFieldName($field);
    }

    if (empty($field)) {
      return '';
    }

    $img_fields = [
      'field_logo_idp',
      'field_logo_servizio_spid',
    ];
    $description_column = in_array($field, $img_fields) ? 'alt' : 'description';
    $query = $this->select('field_data_' . $field, 'f');
    $query->fields('f', [$field . '_' . $description_column]);
    $query->condition($field . '_fid', $this->getFid());
    return $query->execute()->fetchField();
  }

}
