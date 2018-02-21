<?php

namespace Drupal\agid_migrate_tools\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\migrate\Plugin\migrate\process\Migration;

/**
 * Debugging process plugin.
 *
 * @MigrateProcessPlugin(
 *   id = "dump"
 * )
 */
class Dump extends Migration {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    dump($value);
    return $value;
  }

}
