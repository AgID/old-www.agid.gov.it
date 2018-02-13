<?php

namespace Drupal\agid_fixtures\Plugin\migrate\process;

use Drupal\Core\Database\Connection;
use Drupal\Core\Database\Database;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateSkipProcessException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Retrieve the CAD term ID from a node.
 *
 * Examples:
 *
 * @code
 * process:
 *   field_cad:
 *     plugin: get_cad_term_id_from_node
 * @endcode
 *
 * @MigrateProcessPlugin(
 *   id = "get_cad_term_id_from_node"
 * )
 */
class GetCadTermIDFromNode extends ProcessPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager interface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The database connection instance.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $db;

  /**
   * An array used to store articles ids.
   *
   * @var array
   */
  protected $articles = [];

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, Connection $database) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->db = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // Validate the configuration.
    if (empty($value['nid'])) {
      throw new MigrateException('Get CAD term id from node plugin is missing the nid key value.');
    }

    $nid = $value['nid'];
    $article = $this->getArticleNumberByNid($nid);

    if (empty($article)) {
      throw new MigrateSkipProcessException('Cannot load node with id ' . $nid);
    }

    // Extract CAD term from article number.
    $term = $this->extractCadTidByArticleNumber($article);
    if (empty($term)) {
      throw new MigrateSkipProcessException('Cannot find CAD article ' . $article);
    }

    $value = $term;
    $source_nid = $row->getSourceProperty('nid');

    // Avoid duplicated terms.
    if (isset($this->articles[$source_nid]) && in_array($value, $this->articles[$source_nid])) {
      return NULL;
    }

    // Store used tid for this row.
    $this->articles[$source_nid][] = $value;
    return $value;
  }

  /**
   * Get Article number by nid.
   *
   * @param int $nid
   *   The cad_article node id.
   *
   * @return int|null
   *   The article number or null if does not exists.
   */
  protected function getArticleNumberByNid($nid) {
    // Switch to external database.
    Database::setActiveConnection('db_migration');

    // Get a connection going.
    $db = Database::getConnection();
    $query = $db->select('field_data_field_cadarticolo', 'ca');
    $query->fields('ca', ['field_cadarticolo_value']);
    $query->condition('ca.entity_id', $nid);
    $query->condition('ca.bundle', 'cad_articolo');
    $article = $query->execute()->fetchField();

    // Switch back.
    Database::setActiveConnection();
    return $article;
  }

  /**
   * Extract CAD tid by article number.
   *
   * @param int $article
   *   The article number.
   *
   * @return int|null
   *   The CAD tid related to an article number.
   */
  protected function extractCadTidByArticleNumber($article) {
    $name = 'Art. ' . $article . ' - ';
    $term_storage = $this->entityTypeManager->getStorage('taxonomy_term');
    $query = $term_storage->getQuery();
    $query->condition('vid', 'cad');
    $query->condition('name', $this->db->escapeLike($name) . '%', 'LIKE');
    $query->range(0, 1);

    $result = $query->execute();
    return !empty($result) ? reset($result) : NULL;
  }

}
