<?php

namespace Drupal\agid_fixtures\Plugin\migrate\source;

use Drupal\migrate_plus\Plugin\migrate\source\Url;
use Drupal\migrate\Row;

/**
 * Menu link base migration.
 *
 * Source:
 *   plugin: agidfixturesmenulinks.
 *
 * @MigrateSource(
 *   id = "agidfixturesmenulinks"
 * )
 */
class AgidFixturesMenuLinks extends Url {

  /**
   * Migration source file.
   */

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {

    $options = [];

    $row->setDestinationProperty('options', $options);
    $row->setSourceProperty('options', $options);
    return parent::prepareRow($row);
  }

}
