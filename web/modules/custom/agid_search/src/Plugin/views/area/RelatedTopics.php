<?php

namespace Drupal\agid_search\Plugin\views\area;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\search_api\Entity\Index;
use Drupal\search_api\Query\ResultSetInterface;
use Drupal\views\Plugin\views\area\AreaPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RelatedTopics.
 *
 * @ingroup views_area_handlers
 *
 * @ViewsArea("agid_search_related_topics_solr")
 */
class RelatedTopics extends AreaPluginBase {

  /**
   * Term storage.
   *
   * @var \Drupal\taxonomy\TermStorageInterface
   */
  protected $termStorage;

  /**
   * RelatedTopics constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->termStorage = $entity_type_manager->getStorage('taxonomy_term');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('entity_type.manager'));
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['search_api_field'] = ['default' => ''];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $fields = $this->getIndexFields();
    $form['search_api_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Search Api field'),
      '#default_value' => $this->options['search_api_field'],
      '#options' => $fields,
      '#description' => $this->t('Select the field used for build the reference topics.'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    if ($this->query
      && $this->query->getIndex()->getServerInstance()
        ->supportsFeature('search_api_facets')
    ) {
      $field = $this->options['search_api_field'];
      $this->query->setOption('search_api_facets', [
        $field => [
          // The Search API field ID.
          'field' => $field,
          // The maximum number of filters to retrieve for the facet.
          'limit' => 10,
          // The facet operator: "and" or "or".
          'operator' => "and",
          // The minimum count a filter/value must have to be returned.
          'min_count' => 0,
          // Whether to retrieve a facet for "missing" values.
          'missing' => FALSE,
        ],
      ]);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function render($empty = FALSE) {

    if (!$this->query->getIndex()->getServerInstance()
      ->supportsFeature('search_api_facets')
    ) {
      return [];
    }

    $field = $this->options['search_api_field'];
    $index = $this->query->getIndex();
    $server_instance = $index->getServerInstance();
    $backend_plugin = $server_instance->getBackend();

    // Retrieve a fields table name map.
    $field_names = $backend_plugin->getSolrFieldNames($index);

    if (!isset($field_names[$field])) {
      // Not found field in mapping solr fields.
      return [];
    }

    // Retrieve results.
    $results = $this->query->getSearchApiResults();

    if (!$results instanceof ResultSetInterface) {
      // Not found results.
      return [];
    }

    // Retrieve the 'search_api_facets'.
    $facet_results = $results->getExtraData('search_api_facets');

    if ($facet_results === []) {
      // If no data is found in the 'search_api_facets' extra data.
      return [];
    }

    // Get results for field.
    $results_data = isset($facet_results[$field]) ? $facet_results[$field] : [];

    // TODO: load the data from the facets and retrieve the terms of taxonomy referenced to them.

    $tids = [];
    foreach ($results_data as $result_data) {
      $count = $result_data['count'];
      $result_filter = trim($result_data['filter'], '"');
      if ($count > 1) {
        $tids[] = $result_filter;
      }
    }

    // TODO: retrieve term referenced with this terms.

    $links = [];
    $terms = $this->termStorage->loadMultiple($tids);
    foreach ($terms as $term) {
      $links[] = [
        'title' => $term->label(),
        'url' => $term->toUrl(),
      ];
    }

    return [
      '#theme' => 'links',
      '#heading' => ['text' => t('Related topics')],
      '#attributes' => [
        'class' => ['links', 'inline'],
      ],
      '#links' => $links,
    ];
  }

  /**
   * Retrieves a list of all available fields.
   *
   * @return string[]
   *   An options list of field identifiers mapped to their labels.
   */
  protected function getIndexFields() {
    $fields = [];
    /** @var \Drupal\search_api\IndexInterface $index */
    $index = Index::load(substr($this->table, 17));

    $fields_info = $index->getFields();
    foreach ($fields_info as $field_info) {
      $fields[$field_info->getFieldIdentifier()] = $field_info->getLabel();
    }
    return $fields;
  }

}
