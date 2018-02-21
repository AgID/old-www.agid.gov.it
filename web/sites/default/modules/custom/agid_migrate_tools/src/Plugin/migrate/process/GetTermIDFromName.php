<?php

namespace Drupal\agid_migrate_tools\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Retrive the term ID from a string.
 *
 * If a term with that name (the string input) exist in the given vocabulary,
 * this plugin retrive the term ID.
 *
 * Available configuration keys
 * - vocabulary: The vocabulary machine name where we want to search the term
 *
 * Examples:
 *
 * @code
 * process:
 *   field_taxonomy_term:
 *     plugin: get_term_id_from_name
 *     vocabulary_name: 'vocabulary_machine_name'
 *     source: 'the_term_name'
 * @endcode
 *
 * @MigrateProcessPlugin(
 *   id = "get_term_id_from_name"
 * )
 */
class GetTermIDFromName extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $term = $this->termExists($value);

    // If we do not have a term with that name, return null, otherwise we
    // return the term ID.
    // Please note that we always have only one term since we process one
    // string at a time.
    return array_pop(array_keys($term));
  }

  /**
   * Function that validate the vocabulary name passed to the configs
   *
   * @return string
   *    The vocabulary name passed.
   *
   * @throws \Drupal\migrate\MigrateException
   */
  protected function getVocabularyName() {
    // Validation of the configuration.
    if (empty($this->configuration['vocabulary_name'])) {
      throw new MigrateException('Get term id from name plugin is missing the vocabulary name configuration.');
    }
    return $this->configuration['vocabulary_name'];
  }

  /**
   * Function that returns the Term ID if exists
   *
   * @param string $value
   *   The readable term value.
   *
   * @return array|null
   *   The array of matching term objects, NULL otherwise.
   *
   * @throws
   *  MigrateException exception
   */
  protected function termExists($value) {
    $vocabulary_name = $this->getVocabularyName();

    $variations = [
      $value,
      str_replace('_', ' ', $value)
    ];

    foreach ($variations as $variation) {
      $term = taxonomy_term_load_multiple_by_name($variation, $vocabulary_name);
      if (!empty($term)) {
        return $term;
      }
    }
    return [];
  }

}
