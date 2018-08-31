<?php

namespace Drupal\agid_migrate_tools\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\taxonomy\Entity\Term;

/**
 * Plugin that manage terms, passing vocabulary name and the value of the term.
 * If the term doesn't exist will be inserted into the passed vocabulary,
 * and returned the new term ID, instead, if already exists will be returned
 * the term ID.
 *
 * Available configuration keys
 * - vocabulary_name:
 *   The vocabulary machine name where we want to search the term.
 *
 * Examples:
 *
 * @code
 * process:
 *   field_taxonomy_term:
 *     plugin: insert_or_select_taxonomy_term
 *     vocabulary_name: 'vocabulary_machine_name'
 *     source: 'the_term_name'
 * @endcode
 *
 * @MigrateProcessPlugin(
 *   id = "insert_or_select_taxonomy_term"
 * )
 */
class InsertOrSelectTaxonomyTerm extends GetTermIDFromName {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $term = $this->termExists($value);
    if (!empty($term)) {
      return key($term);
    }

    // If passed vocabulary exists but not the passed term value,
    // so a new term will be created.
    $new_term = Term::create([
      'name' => $value,
      'vid' => $this->getVocabularyName(),
    ]);
    $new_term->save();
    return $new_term;
  }

}
