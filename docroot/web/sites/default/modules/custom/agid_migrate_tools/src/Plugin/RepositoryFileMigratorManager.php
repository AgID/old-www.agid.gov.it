<?php

namespace Drupal\agid_migrate_tools\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Repository file migration plugin manager.
 */
class RepositoryFileMigratorManager extends DefaultPluginManager {

  /**
   * Constructor for RepositoryFileMigrationManager objects.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/migrate/repository_file_migrator',
      $namespaces,
      $module_handler,
      'Drupal\agid_migrate_tools\Plugin\RepositoryFileMigratorInterface',
      'Drupal\agid_migrate_tools\Annotation\RepositoryFileMigrator');

    $this->setCacheBackend($cache_backend, 'agid_fixtures_repository_file_migrator_plugins');
    $this->alterInfo('agid_fixtures_repository_file_migrator_info');
  }

}
