<?php

namespace Drupal\agid_fixtures;

use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Drupal\migrate\Row;

/**
 * Class AgidFixturesRepositoryFileMigrationFactory.
 *
 * @package Drupal\agid_fixtures
 */
class AgidFixturesRepositoryFileMigrationFactory implements AgidFixturesRepositoryFileMigrationFactoryInterface {

  use ContainerAwareTrait;

  /**
   * An array of migrators.
   *
   * @var array
   */
  protected $migrators = [];

  /**
   * The Repository file migrator plugin manager.
   *
   * @var \Drupal\agid_fixtures\Plugin\RepositoryFileMigratorManager
   */
  protected $repositoryFileMigratorManager;

  /**
   * {@inheritdoc}
   */
  public function get(Row $row, Connection $database) {
    if (!$row->hasSourceProperty('fid')) {
      $plugin = $row->getSourceProperty('plugin');
      $id = $row->getSourceProperty('migrate_map_sourceid1');
      $message = sprintf('Cannot instantiate a repository file migrator for plugin "%s" with id "%s"', $plugin, $id);
      throw new \InvalidArgumentException($message);
    }

    $this->loadDefinitions();
    $repositoryFileMigratorManager = $this->getRepositoryFileMigratorManager();
    $file_info = $this->getFileUsageInfo($row, $database);

    // Search for bundle specific migrator if exists, otherwise try to find the
    // most generic entity migrator.
    $keys = [
      implode('-', array_filter($file_info)),
      $file_info['module'] . '-' . $file_info['type'],
      $file_info['module'],
    ];

    $fid = $row->getSourceProperty('fid');
    foreach ($keys as $key) {
      if (!isset($this->migrators[$key])) {
        continue;
      }

      // If the migrator value is not an object, we need to create the plugin
      // instance.
      if (!is_object($this->migrators[$key]) && $repositoryFileMigratorManager->hasDefinition($this->migrators[$key])) {
        $migrator = $repositoryFileMigratorManager->createInstance($this->migrators[$key]);
        $this->migrators[$key] = $migrator;
      }

      $this->migrators[$key]->setFid($fid);
      $this->migrators[$key]->setRow($row);
      $this->migrators[$key]->setDatabase($database);

      return $this->migrators[$key];
    }

    $message = sprintf('Missing migrator for file with fid %s created by %s module and of type %s and bundle %s', $fid, $file_info['module'], $file_info['type'], $file_info['bundle']);
    throw new \RuntimeException($message);
  }

  /**
   * Get file usage info.
   *
   * @param \Drupal\migrate\Row $row
   *   The migration row.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   *
   * @return array
   *   An array containing info about module, type and bundle.
   */
  protected function getFileUsageInfo(Row $row, Connection $database) {
    $info = [];
    $properties = [
      'module',
      'type',
      'bundle',
    ];

    $query = $database->select('file_usage', 'fu', ['fetch' => \PDO::FETCH_ASSOC]);
    $query->fields('fu');
    $query->condition('fu.fid', $row->getSourceProperty('fid'));
    $file_usage = $query->execute()->fetch();

    foreach ($properties as $property) {
      if ($row->hasSourceProperty($property)) {
        $info[$property] = $row->getSourceProperty($property);
      }
      else {
        $info[$property] = isset($file_usage[$property]) ? $file_usage[$property] : NULL;
      }
    }

    if ($info['type'] == 'node' && empty($info['bundle'])) {
      $query = $database->select('node', 'n', ['fetch' => \PDO::FETCH_ASSOC]);
      $query->fields('n', ['type']);
      $query->condition('nid', $file_usage['id']);
      $info['bundle'] = $query->execute()->fetchField();
    }

    return $info;
  }

  /**
   * Loads migrators definitions list.
   *
   * Builds a migrators definitions list in the form of an associative array,
   * where the keys are the concatenation of entity type and bundle properties,
   * and values are only the plugin definition id. This results in a sort of
   * lazy loading of plugin instances, which will be created only if necessary.
   */
  protected function loadDefinitions() {
    if (!empty($this->migrators)) {
      return;
    }

    $repositoryFileMigratorManager = $this->getRepositoryFileMigratorManager();
    foreach ($repositoryFileMigratorManager->getDefinitions() as $definition) {
      $bundles = !empty($definition['bundles']) ? $definition['bundles'] : [0];

      foreach ($bundles as $bundle) {
        $keys = array_filter([
          $definition['module'],
          $definition['entityType'],
          $bundle,
        ]);
        $key = implode('-', $keys);
        $this->migrators[$key] = $definition['id'];
      }
    }
  }

  /**
   * Get Repository File Migrator Plugin Manager.
   *
   * @return \Drupal\agid_fixtures\Plugin\RepositoryFileMigratorManager
   *   The Repository File Migrator Plugin Manager.
   */
  protected function getRepositoryFileMigratorManager() {
    if (!isset($this->repositoryFileMigratorManager)) {
      $id = 'plugin.manager.agid_fixtures.repository_file_migrator';
      $this->repositoryFileMigratorManager = $this->container->get($id);
    }
    return $this->repositoryFileMigratorManager;
  }

}
