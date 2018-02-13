<?php

namespace Drupal\agid_fixtures\Plugin\migrate\process;

use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * This plugin skip migration row if a file does not exists.
 *
 * @MigrateProcessPlugin(
 *   id = "check_file"
 * )
 */
class CheckFile extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $filepath = $this->configuration['files_source_dir'] . '/' . $value;
    if (!file_exists($filepath)) {
      $message = t('File "@value" does not exists.', ['@value' => $value]);
      drupal_set_message($message);
      throw new MigrateSkipRowException($message);
    }

    return $value;
  }

}
