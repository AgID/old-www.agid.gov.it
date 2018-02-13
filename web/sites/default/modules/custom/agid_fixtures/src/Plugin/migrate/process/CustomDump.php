<?php

namespace Drupal\agid_fixtures\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * This plugin replace dump values for debugging.
 *
 * @MigrateProcessPlugin(
 *   id = "custom_dump"
 * )
 */
class CustomDump extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    drush_print_r($value);
    return $value;
  }

}
