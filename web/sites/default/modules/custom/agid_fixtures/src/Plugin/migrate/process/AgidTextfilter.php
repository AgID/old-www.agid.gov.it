<?php

namespace Drupal\agid_fixtures\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Process the date from db (YYYY-MM-DD H:i:s) into a D8 compatible timestamp.
 *
 * @MigrateProcessPlugin(
 *   id = "agid_textfilter"
 * )
 */
class AgidTextfilter extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    // Strip tags or inline style from source.
    $filtered_value = strip_tags($value['value'], '<br><p><ul><ol><li><strong><em><a>');
    // Remove old AGID site base url.
    $filtered_value = str_replace('http://www.agid.gov.it', '', $filtered_value);
    // Update files path with new repository_files directory.
    $filtered_value = str_replace('sites/default/files', 'sites/default/files/repository_files', $filtered_value);
    // Decode HTML entities.
    $filtered_value = html_entity_decode($filtered_value);

    // In 8.3 version of core it seems not possible to set filter format
    // inside migration file (see https://www.drupal.org/node/2789125).
    // We add filter format here.
    $filtered_format = 'filtered_text';
    $filtered_elements = [
      'value' => $filtered_value,
      'format' => $filtered_format,
    ];

    return $filtered_elements;
  }

}
