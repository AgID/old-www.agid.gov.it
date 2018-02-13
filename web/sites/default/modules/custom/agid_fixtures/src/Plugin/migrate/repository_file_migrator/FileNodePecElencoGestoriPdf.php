<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class FileNodePecElencoGestoriPdf.
 *
 * @RepositoryFileMigrator(
 *   id = "file_node_pec_elenco_gestori_pdf_migrator",
 *   name = @Translation("File Repository Migrator - File Node PEC - elenco pubblico gestori - pdf firmato"),
 *   module = "file",
 *   entityType = "node",
 *   bundles = {
 *    "pec_elenco_gestori_pdf"
 *   }
 * )
 */
class FileNodePecElencoGestoriPdf extends RepositoryFileMigratorBase {

  /**
   * Tid in use for "elenco pubblico gestori PEC" term from "agidold" database.
   */
  const PEC_MANAGERS_PUBLIC_LIST = 87;

  /**
   * {@inheritdoc}
   */
  public function getFileTitle() {
    $query = $this->select('node', 'n');
    $query->fields('n', ['title']);
    $query->condition('n.nid', $this->getEntityId());
    return $query->execute()->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return 'field_pec_pdf_firma';
  }

  /**
   * {@inheritdoc}
   */
  public function getFileArguments() {
    return [
      [
        'tid' => self::PEC_MANAGERS_PUBLIC_LIST,
      ],
    ];
  }

}
