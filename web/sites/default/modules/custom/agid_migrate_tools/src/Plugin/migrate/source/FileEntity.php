<?php

namespace Drupal\agid_migrate_tools\Plugin\migrate\source;

use Drupal\agid_migrate_tools\AgidFixturesRepositoryFileMigrationFactoryInterface;
use Drupal\Core\Database\Query\Condition;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\State\StateInterface;
use Drupal\file\Plugin\migrate\source\d7\File;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Drupal 7 file_entity source from database.
 *
 * @MigrateSource(
 *   id = "file_entity",
 *   source_provider = "file"
 * )
 */
class FileEntity extends File {

  /**
   * Repository File Migrators Factory.
   *
   * @var \Drupal\agid_migrate_tools\AgidFixturesRepositoryFileMigrationFactoryInterface
   */
  protected $migratorsFactory;

  /**
   * Repository file migration logger channel.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    MigrationInterface $migration,
    StateInterface $state,
    EntityManagerInterface $entity_manager,
    AgidFixturesRepositoryFileMigrationFactoryInterface $migrators_factory,
    LoggerInterface $logger_channel) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration, $state, $entity_manager);

    $this->migratorsFactory = $migrators_factory;
    $this->logger = $logger_channel;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration = NULL) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $migration,
      $container->get('state'),
      $container->get('entity.manager'),
      $container->get('agid_migrate_tools.repository_file_migration.factory'),
      $container->get('logger.factory')->get('repository_file_migration')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('file_managed', 'f')
      ->fields('f')
      ->orderBy('f.fid');
    $query->innerJoin('file_usage', 'fu', 'f.fid = fu.fid');
    if (isset($this->configuration['module'])) {
      $query->condition('fu.module', $this->configuration['module']);
    }
    if (isset($this->configuration['excluded_modules'])) {
      $modules = $this->configuration['excluded_modules'];
      if (!is_array($modules)) {
        $modules = [$modules];
      }
      $query->condition('fu.module', $modules, 'NOT IN');
    }
    if (isset($this->configuration['type'])) {
      $query->condition('fu.type', $this->configuration['type']);
    }
    if (isset($this->configuration['bundle'])) {
      $query->innerJoin('node', 'n', 'fu.id = n.nid');
      $query->condition('n.type', $this->configuration['bundle']);
    }
    // Filter by scheme(s), if configured.
    if (isset($this->configuration['scheme'])) {
      $schemes = [];
      // Accept either a single scheme, or a list.
      foreach ((array) $this->configuration['scheme'] as $scheme) {
        $schemes[] = rtrim($scheme) . '://';
      }
      $schemes = array_map([$this->getDatabase(), 'escapeLike'], $schemes);

      // The uri LIKE 'public://%' OR uri LIKE 'private://%'.
      $conditions = new Condition('OR');
      foreach ($schemes as $scheme) {
        $conditions->condition('uri', $scheme . '%', 'LIKE');
      }
      $query->condition($conditions);
    }

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids = parent::getIds();
    $ids['fid']['alias'] = 'f';
    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    try {
      $migrator = $this->migratorsFactory->get($row, $this->getDatabase());
    }
    catch (\Exception $e) {
      $this->logger->warning($e->getMessage());
      return FALSE;
    }

    $row->setSourceProperty('file_title', $migrator->getFileTitle());
    $row->setSourceProperty('file_description', $migrator->getFileDescription());
    $row->setSourceProperty('file_arguments', $migrator->getFileArguments());
    $row->setSourceProperty('file_source', $migrator->getFileSource());
    $row->setSourceProperty('file_type', $migrator->getFileType());

    return parent::prepareRow($row);
  }

}
