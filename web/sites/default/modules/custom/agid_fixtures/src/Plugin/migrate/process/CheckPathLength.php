<?php

namespace Drupal\agid_fixtures\Plugin\migrate\process;

use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * This plugin replace path value updating its length to the specified value.
 *
 * @MigrateProcessPlugin(
 *   id = "check_path_length"
 * )
 */
class CheckPathLength extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!isset($this->configuration['max'])) {
      return $value;
    }

    $max = $this->configuration['max'];
    $path = $value;
    $path_length = strlen($path);

    if ($path_length <= $max) {
      return $value;
    }

    $path_parts = pathinfo($value);
    $dir_name = $path_parts['dirname'];
    $base_name = $path_parts['basename'];
    $extension = $path_parts['extension'];
    $file_name = $path_parts['filename'];
    $available_length = $max - strlen($dir_name);

    if ($available_length <= 0) {
      $message = t('Path "@value" length (@length) exceeds maximum length of @max.', [
        '@value' => $value,
        '@length' => $path_length,
        '@max' => $max,
      ]);
      drupal_set_message($message);
      throw new MigrateSkipRowException($message);
    }

    $file_length = strlen($base_name);
    $diff = $available_length - ($file_length + 1);
    $new_file_name = substr($file_name, 0, $diff) . '.' . $extension;
    return "$dir_name/$new_file_name";
  }

}
