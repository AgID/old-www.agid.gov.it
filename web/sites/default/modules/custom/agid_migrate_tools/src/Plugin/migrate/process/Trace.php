<?php

namespace Drupal\agid_migrate_tools\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * This plugin prints the value with a carriage-return.
 *
 * @MigrateProcessPlugin(
 *   id = "trace"
 * )
 */
class Trace extends ProcessPluginBase {

  public static $keys = [];

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $key = $this->configuration['key'];
    if (!isset(self::$keys[$key])) {
      self::$keys[$key] = 0;
    }

    $count = self::$keys[$key]++;
    $label = !empty($this->configuration['show_key']) ? "[$key] - " : '';

    print ' ' . $label . "Processed $count items\r";
    return $value;
  }

}
