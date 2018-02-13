<?php

namespace Drupal\agid_fixtures\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for Repository file migration plugins.
 */
abstract class RepositoryFileMigratorBase extends PluginBase implements RepositoryFileMigratorInterface, ContainerFactoryPluginInterface {

  /**
   * The file id.
   *
   * @var int
   */
  protected $fid;

  /**
   * The migrated row.
   *
   * @var \Drupal\migrate\Row
   */
  protected $row;

  /**
   * The database object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * An array containing id and type of the entity related to the file.
   *
   * @var array
   */
  protected $entityInfo;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setFid($fid) {
    $this->fid = $fid;
  }

  /**
   * {@inheritdoc}
   */
  public function getFid() {
    return $this->fid;
  }

  /**
   * {@inheritdoc}
   */
  public function setRow(Row $row) {
    $this->row = $row;
  }

  /**
   * {@inheritdoc}
   */
  public function getRow() {
    return $this->row;
  }

  /**
   * {@inheritdoc}
   */
  public function getFileTitle() {
    $filename = $this->getRow()->getSourceProperty('filename');
    $filename = pathinfo($filename, PATHINFO_FILENAME);
    $title = preg_replace('/\_+/', ' ', trim($filename));
    return ucfirst($title);
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

    $query = $this->select('field_data_' . $field, 'f');
    $query->fields('f', [$field . '_description']);
    $query->condition($field . '_fid', $this->getFid());
    return $query->execute()->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function getFileDescriptionField() {
    return NULL;
  }

  /**
   * Extract field name.
   *
   * @param array $fields
   *   An array of field names.
   *
   * @return string|null
   *   The field name or NULL.
   */
  protected function extractFieldName(array $fields) {
    foreach ($fields as $field_name) {
      if ($this->checkFieldName($field_name)) {
        return $field_name;
      }
    }

    return NULL;
  }

  /**
   * Check field name.
   *
   * @param string $field
   *   The field name.
   *
   * @return object
   *   An object containing data related to the given field by file id.
   */
  protected function checkFieldName($field) {
    $query = $this->select('field_data_' . $field, 'f');
    $query->fields('f');
    $query->condition($field . '_fid', $this->getFid());
    return $query->execute()->fetch();
  }

  /**
   * {@inheritdoc}
   */
  public function getFileArguments() {
    $field = $this->getFileArgumentsField();
    if (empty($field)) {
      return [];
    }

    $query = $this->select('field_data_' . $field, 'ft');
    $query->fields('ft', [$field . '_tid']);
    $query->condition('ft.entity_type', 'node');
    $query->condition('ft.entity_id', $this->getEntityId());

    $arguments = [];
    foreach ($query->execute()->fetchCol() as $tid) {
      $arguments[]['tid'] = $tid;
    }
    return $arguments;
  }

  /**
   * {@inheritdoc}
   */
  public function getFileArgumentsField() {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function setDatabase(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public function getFileSource() {
    $source = [];
    if ($this->getEntityType() != 'node') {
      return $source;
    }

    $terms_storage = $this->entityTypeManager->getStorage('taxonomy_term');
    $terms = $terms_storage->loadByProperties([
      'field_original_bundle' => $this->getNodeBundle(),
      'vid' => 'original_file_source',
    ]);

    if (!empty($terms)) {
      $tid = key($terms);
      $source[] = ['tid' => $tid];
    }

    return $source;
  }

  /**
   * {@inheritdoc}
   */
  public function getFileType() {
    $type = [];
    if ($this->getEntityType() != 'node') {
      return $type;
    }

    $field_name = $this->getFileDescriptionField();
    if (empty($field_name)) {
      return $type;
    }

    if (is_array($field_name)) {
      $field_name = $this->extractFieldName($field_name);
    }

    $query = $this->getDatabase()->select('field_config_instance', 'fc');
    $query->fields('fc', ['data']);
    $query->condition('fc.field_name', $field_name);
    $query->condition('fc.entity_type', $this->getEntityType());
    $query->condition('fc.bundle', $this->getNodeBundle());
    $data = $query->execute()->fetchField();

    if (empty($data)) {
      return $type;
    }

    $data = unserialize($data);
    $label = $data['label'];

    $term_storage = $this->entityTypeManager->getStorage('taxonomy_term');
    $terms = $term_storage->loadByProperties([
      'name' => $label,
      'vid' => 'file_type',
    ]);

    if (!empty($terms)) {
      $tid = key($terms);
      $type[] = ['tid' => $tid];
    }

    return $type;
  }

  /**
   * {@inheritdoc}
   */
  public function getDatabase() {
    return $this->database;
  }

  /**
   * Wrapper for database select.
   *
   * @param string $table
   *   The table name.
   * @param string|null $alias
   *   The table alias.
   * @param array $options
   *   An associative array of query options.
   *
   * @return \Drupal\Core\Database\Query\SelectInterface
   *   The select query instance.
   */
  protected function select($table, $alias = NULL, array $options = []) {
    $options['fetch'] = \PDO::FETCH_ASSOC;
    return $this->getDatabase()->select($table, $alias, $options);
  }

  /**
   * Get id of the entity related to a file by its fid.
   *
   * @return int
   *   The id of the entity related to the given file id.
   */
  protected function getEntityId() {
    $info = $this->getEntityInfo();
    return $info['id'];
  }

  /**
   * Get type of the entity related to a file by its fid.
   *
   * @return string
   *   The type of the entity related to the given file id.
   */
  protected function getEntityType() {
    $info = $this->getEntityInfo();
    return $info['type'];
  }

  /**
   * Get node bundle.
   *
   * @return string|null
   *   The node bundle if the entity is a node, otherwise null.
   */
  protected function getNodeBundle() {
    if ($this->getEntityType() != 'node') {
      return NULL;
    }

    $query = $this->getDatabase()->select('node', 'n');
    $query->fields('n', ['type']);
    $query->condition('n.nid', $this->getEntityId());
    return $query->execute()->fetchField();
  }

  /**
   * Get entity info.
   *
   * @return array
   *   An associative array containing "id" and "type" of the entity.
   */
  protected function getEntityInfo() {
    if (empty($this->entityInfo)) {
      $this->setEntityInfo();
    }
    return $this->entityInfo;
  }

  /**
   * Set entity info.
   */
  protected function setEntityInfo() {
    $query = $this->select('file_usage', 'fu');
    $query->fields('fu', ['id', 'type']);
    $query->condition('fu.fid', $this->getFid());
    $this->entityInfo = $query->execute()->fetch(\PDO::FETCH_ASSOC);
  }

}
