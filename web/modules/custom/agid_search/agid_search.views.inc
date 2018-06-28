<?php

/**
 * @file
 * Views hook implementations for the module.
 */

/**
 * Implements hook_views_data_alter().
 */
function agid_search_views_data_alter(array &$data) {

  /** @var \Drupal\search_api\IndexInterface $index */
  foreach (\Drupal\search_api\Entity\Index::loadMultiple() as $index) {

    try {
      // Fill in base data.
      $key = 'search_api_index_' . $index->id();
      if (empty($data[$key])) {
        // Not found base data.
        return $data;
      }
      $table = &$data[$key];
      $table['agid_search_related_topics_solr'] = [
        'title' => t('Related topics'),
        'help' => t('Displays related topics. This plugin required a solr integration.'),
        'area' => [
          'id' => 'agid_search_related_topics_solr',
        ],
      ];
      $table['agid_search_spellcheck_solr'] = [
        'title' => t('Search api spellcheck with Solr'),
        'help' => t('Displays spell check suggestions.'),
        'area' => [
          'id' => 'agid_search_spellcheck_solr',
        ],
      ];
      $table['agid_search_expose_filter_form'] = [
        'title' => t('Expose filter form'),
        'help' => t('Display the expose filter form.'),
        'area' => [
          'id' => 'agid_search_expose_filter_form',
        ],
      ];

    }
    catch (\Exception $e) {
      $args = [
        '%index' => $index->label(),
      ];
      watchdog_exception('agid_search', $e, '%type while computing Views data for index %index: @message in %function (line %line of %file).', $args);
    }
  }

  return $data;
}
