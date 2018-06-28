<?php

namespace Drupal\agid_search\Plugin\views\area;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\search_api\Plugin\views\filter\SearchApiFulltext;
use Drupal\views\Plugin\views\area\AreaPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides an area for print spellcheck from solr server.
 *
 * @ingroup views_area_handlers
 *
 * @ViewsArea("agid_search_spellcheck_solr")
 */
class SpellCheckSolr extends AreaPluginBase {

  /**
   * Current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $current_request;

  /**
   * SpellCheckSolr constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RequestStack $request_stack) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->current_request = $request_stack->getCurrentRequest();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('request_stack')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('The title to announce the suggestions.'),
      '#default_value' => isset($this->options['title']) ? $this->options['title'] : '',
    ];
    $form['hide_on_result'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hide when the view has results.'),
      '#default_value' => isset($this->options['hide_on_result']) ? $this->options['hide_on_result'] : TRUE,
    ];
    $form['redirect'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Active redirect when results is empty and found a suggestion/spellcheck valid.'),
      '#default_value' => isset($this->options['redirect']) ? $this->options['redirect'] : TRUE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    /** @var \Drupal\search_api\Plugin\views\query\SearchApiQuery $query */
    $query = &$this->query;
    $spellcheck_options = [
      'onlymorepopular' => TRUE,
      'count' => 10,
      'collate' => TRUE,
    ];
    $query->setOption('search_api_spellcheck', $spellcheck_options);
    // Regarding third-party features how spellcheck, in search api solr are
    // currently work in progress for 8.x-2.x. See README.md of search_api_solr.
    // @see agid_search_search_api_solr_query_alter().
  }

  /**
   * Render the area.
   *
   * @param bool $empty
   *   (optional) Indicator if view result is empty or not. Defaults to FALSE.
   *
   * @return array
   *   In any case we need a valid Drupal render array to return.
   */
  public function render($empty = FALSE) {
    $build = [];
    if ($this->options['hide_on_result'] == FALSE || ($this->options['hide_on_result'] && $empty)) {

      $suggestions = $this->getSpellcheck();
      $current_query = $this->current_request->query->all();

      foreach ($suggestions as $suggestion) {
        $filter = $suggestion['value'];

        // Merge the query parameters.
        if (is_array($current_query)) {
          $filter = array_merge($current_query, $filter);
        }

        // Add the suggestion.
        $suggestion_links[] = [
          '#type' => 'link',
          '#title' => reset($filter),
          '#url' => Url::fromRoute('<current>', [], ['query' => $filter]),
        ];
      }

      if (!empty($suggestion_links)) {
        $build['suggestions'] = [
          '#title' => $this->getSuggestionLabel(),
          '#theme' => 'item_list',
          '#items' => $suggestion_links,
        ];
      }
    }

    return $build;
  }

  /**
   * Get suggestions for current query.
   */
  public function getSpellcheck() {
    // Initialize our array.
    $suggestions = [];

    // Retrieve results.
    $result = $this->query->getSearchApiResults();

    // Check if extraData is there.
    if ($extra_data = $result->getExtraData('search_api_solr_response')) {
      // Check that we have suggestions.
      if (!empty($extra_data['spellcheck'])) {

        // Retrieve a filters from view.
        $filters = $this->getFilters();

        // Loop over the suggestions.
        foreach ($extra_data['spellcheck'] as $suggestion) {
          if (count($suggestion) != 0 && $filter = $this->getFilterMatch($filters, $suggestion)) {
            $suggestions[] = [
              'value' => $filter,
              'raw' => $suggestion,
            ];
          }
        }
      }
    }
    return $suggestions;
  }

  /**
   * Check and redirect page if feature is active.
   *
   * The user will be directed to the page with the suggested term inserted
   * as a search term.
   */
  public function isRedirected() {

    if ($this->view->total_rows == 0 && $this->options['redirect']) {

      // Retrieve spellcheck.
      $spellecheck_data = reset($this->getSpellcheck());
      if (empty($spellecheck_data)) {
        return;
      }
      $spellcheck = $spellecheck_data['value'];

      // Merge the query parameters.
      $current_query = \Drupal::requestStack()
        ->getCurrentRequest()->query->all();
      $query_parameters = [];
      if (is_array($current_query)) {
        $query_parameters = array_merge($current_query, $spellcheck);
      }

      // Add prev filter used.
      $prev_filters = $this->getFilters();
      foreach ($spellcheck as $k => $v) {
        if (isset($prev_filters[$k])) {
          $query_parameters['prev_' . $k] = $prev_filters[$k];
        }
      }

      // Create Url for redirect and execute redirect.
      $url = Url::fromRoute('<current>', [], ['query' => $query_parameters]);
      $this->redirect($url);
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['title']['default'] = '';
    $options['hide_on_result']['default'] = TRUE;
    $options['redirect']['default'] = FALSE;
    return $options;
  }

  /**
   * Gets the suggestion label.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   The suggestion label translated.
   */
  protected function getSuggestionLabel() {
    return !empty($this->options['title']) ? $this->options['title'] : $this->t('Suggestions:');
  }

  /**
   * Gets the matching filter for the suggestion.
   *
   * @param array $suggestion
   *   The suggestion array.
   *
   * @return array
   *   False or the matching filter.
   */
  protected function getFilterMatch(array $filters, array $suggestion) {
    if ($index = array_search($suggestion[0], $filters, TRUE)) {
      // @todo: Better validation.
      if (!empty($suggestion[1]['suggestion'][0])) {
        return [$index => $suggestion[1]['suggestion'][0]];
      }
    }
  }

  /**
   * Gets a list of filters of view.
   *
   * @return array
   *   The filters by key value.
   */
  protected function getFilters() {
    $filters = [];
    $exposed_input = $this->view->getExposedInput();
    foreach ($this->view->filter as $key => $filter) {
      if ($filter instanceof SearchApiFulltext) {
        // The filter could be different then the key.
        if (!empty($filter->options['expose']['identifier'])) {
          $key = $filter->options['expose']['identifier'];
        }
        $filters[$key] = !empty($exposed_input[$key]) ? $exposed_input[$key] : FALSE;
      }
    }
    return $filters;
  }

  /**
   * Force redirect to url.
   *
   * @param \Drupal\Core\Url $url
   *   Es: Url::fromRoute('<current>', [], ['query' => $filter]
   */
  protected function redirect(Url $url) {
    $response = new \Symfony\Component\HttpFoundation\RedirectResponse($url->toString());
    $request = \Drupal::request();
    // Save the session so things like messages get saved.
    $request->getSession()->save();
    $response->prepare($request);
    // Make sure to trigger kernel events.
    \Drupal::service('kernel')->terminate($request, $response);
    $response->send();
  }

}
