<?php

namespace Drupal\agid_migrate_tools\Plugin\migrate\process;

use Drupal\migrate\MigrateException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * This plugin filter keys from an associative array.
 *
 * @MigrateProcessPlugin(
 *   id = "filter_keys"
 * )
 */
class FilterKeys extends ProcessPluginBase {

  /**
   * An array of filtered values.
   *
   * @var array
   */
  protected $filter = [];

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (empty($this->configuration['keys'])) {
      throw new MigrateException('Filter keys plugin is missing the keys configuration.');
    }

    if (!is_array($value)) {
      throw new MigrateException('Filter keys plugin source value must be of the type array.');
    }

    $this->filter = [];
    $items = empty($this->configuration['multiple']) ? [$value] : $value;

    foreach ($items as $item) {
      if (!isset($item[0])) {
        $this->filter($item);
        continue;
      }

      foreach ($item as $elem) {
        $this->filter($elem);
      }
    }

    return $this->getFilter();
  }

  /**
   * Get filter.
   *
   * @return array
   *   An array of filtered values.
   */
  public function getFilter() {
    return $this->filter;
  }

  /**
   * Filter values.
   *
   * @param array $item
   *   An array containing values to be filtered.
   */
  protected function filter(array $item) {
    foreach ($this->configuration['keys'] as $key) {
      if (array_key_exists($key, $item)) {
        $this->filter[] = $item[$key];
      }
    }
  }

}
