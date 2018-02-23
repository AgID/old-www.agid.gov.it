<?php

namespace Drupal\agid_migrate_tools\Plugin\migrate\repository_file_migrator;

use Drupal\agid_migrate_tools\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodeOrganigramma.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_organigramma_migrator",
 *   name = @Translation("File Repository Migrator - File Node Organigramma"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "organigramma"
 *   }
 * )
 */
class FileNodeOrganigramma extends RepositoryFileMigratorBase {

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return [
      'field_immagine_organigramma',
      'field_crriculum',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFileDescription() {

    // If the value for the description field has been already gathered,
    // return the cached value.
    if (!is_null($this->description)) {
      return $this->description;
    }

    $field = $this->getFileDescriptionField();
    if (is_array($field)) {
      $field = $this->extractFieldName($field);
    }

    if (empty($field)) {
      $this->description = '';
      return '';
    }

    $description_column = ($field == 'field_crriculum') ? 'description' : 'alt';
    $query = $this->select('field_data_' . $field, 'f');
    $query->fields('f', [$field . '_' . $description_column]);
    $query->condition($field . '_fid', $this->getFid());
    $this->description = $query->execute()->fetchField();

    return $this->description;
  }

}
