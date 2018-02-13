<?php

namespace Drupal\agid_fixtures\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Repository file migrator item annotation object.
 *
 * @see \Drupal\agid_fixtures\Plugin\RepositoryFileMigrationManager
 * @see plugin_api
 *
 * @Annotation
 */
class RepositoryFileMigrator extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * The name of the module used in the {file_usage} table.
   *
   * @var string
   */
  public $module;

  /**
   * The Entity type name.
   *
   * @var string
   */
  public $entityType;

  /**
   * The Entity bundle names.
   *
   * @var array
   */
  public $bundles;

}
