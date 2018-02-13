<?php

namespace Drupal\agid_fixtures\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Check if the link is a valid D8 URI scheme.
 *
 * This function is copied from
 * web/core/modules/link/src/Plugin/migrate/process/d6/FieldLink.php.
 *
 * @MigrateProcessPlugin(
 *   id = "agid_check_link"
 * )
 */
class AgidCheckLink extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    // If we already have a scheme, we're fine.
    if (empty($value) || !is_null(parse_url($value, PHP_URL_SCHEME))) {
      return $value;
    }

    // If the url start with www. we consider as an external URL and we want to
    // fix it prefixing with "http://".
    if (strpos($value, 'www.') === 0) {
      return 'http://' . $value;
    }

    // Remove the <front> component of the URL.
    if (strpos($value, '<front>') === 0) {
      $value = substr($value, strlen('<front>'));
    }

    // Add the internal: scheme and ensure a leading slash.
    return 'internal:/' . ltrim($value, '/');

  }

}
