<?php

namespace Drupal\agid_blocks\Plugin\Block;

use Drupal\agid_blocks\AgidBlocks;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\views\Entity\View;
use Drupal\views\ViewExecutable;
use Drupal\views\ViewExecutableFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'PagoPACFBlock' block.
 *
 * @Block(
 *  id = "pagopacfblock",
 *  admin_label = @Translation("PagoPa CF Block"),
 * )
 */
class PagoPACFBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The CurrentRouteMatch service.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $currentRouteMatch;

  /**
   * The EntityTypeManager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * The ViewExecutableFactory service.
   *
   * @var \Drupal\views\ViewExecutableFactory
   */
  protected $viewExecutableFactory;

  /**
   * The value for the view filter.
   *
   * @var string
   */
  protected $cf;

  /**
   * The block configuration.
   *
   * @var array
   */
  protected $configuration;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    CurrentRouteMatch $current_route_match,
    EntityTypeManager $entity_type_manager,
    ViewExecutableFactory $view_executable_factory
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentRouteMatch = $current_route_match;
    $this->entityTypeManager = $entity_type_manager;
    $this->viewExecutableFactory = $view_executable_factory;

    $this->cf = $this->retrieveDataFromRoute();

    if ( is_null($this->cf) ) {
      return;
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match'),
      $container->get('entity_type.manager'),
      $container->get('views.executable')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $tag_terms = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree(AgidBlocks::TAXONOMY_PAGOPA_TYPE);
    $tags = array();
    foreach ($tag_terms as $tag_term) {
      $tags[$tag_term->tid] = $tag_term->name;
    }

    $form['pagopa_type_term'] = array(
      '#type' => 'select',
      '#options' => $tags,
      '#title' => $this->t('Select PagoPA type term'),
      '#default_value' => isset($config['pagopa_type_term']) ? $config['pagopa_type_term'] : '',
      '#required' => TRUE,
    );


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $values = $form_state->getValues();

    $this->configuration['pagopa_type_term'] = $values['pagopa_type_term'];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    /** @var View $view */
    $view = $this->entityTypeManager->getStorage('view')->load(AgidBlocks::VIEW_PAGOPA_INFO);

    /** @var ViewExecutable $viewExecutable */
    $viewExecutable = $this->viewExecutableFactory->get($view);

    $viewExecutable->executeDisplay(AgidBlocks::VIEW_PAGOPA_INFO_DISPLAY, [$this->cf]);

    $build['pagopacfblock'] = $viewExecutable->buildRenderable(AgidBlocks::VIEW_PAGOPA_INFO_DISPLAY, [$this->cf]);

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    // With this when your node change your block will rebuild.
    if ($node = $this->currentRouteMatch->getParameter('node')) {
      // If there is node add its cachetag.
      return Cache::mergeTags(parent::getCacheTags(), ['node:' . $node->id()]);
    }
    else {
      // Return default tags instead.
      return parent::getCacheTags();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    // If you depends on \Drupal::routeMatch(),
    // you must set context of this block with 'route' context tag.
    // Every new route this block will rebuild.
    return Cache::mergeContexts(parent::getCacheContexts(), ['route']);
  }

  /**
   * Retrieve data from route to build the block.
   */
  private function retrieveDataFromRoute() {
    
    /** @var \Drupal\node\NodeInterface $node */
    $node = $this->currentRouteMatch->getParameter('node');

    if (empty($node)) {
      return;
    }

    if (empty($node->get(AgidBlocks::FIELD_PAGOPA_TYPE)) || empty($node->get(AgidBlocks::FIELD_PAGOPA_TYPE)->getValue())) {
      return;
    }

    $term_id = $node->get(AgidBlocks::FIELD_PAGOPA_TYPE)->getValue()[0]['target_id'];

    if (empty($term_id) || $term_id !== $this->configuration['pagopa_type_term']) {
      return;
    }

    if (empty($node->get(AgidBlocks::FIELD_PAGOPA_FISCAL_CODE)) || empty($node->get(AgidBlocks::FIELD_PAGOPA_FISCAL_CODE)->getValue())) {
      return;
    }

    return $node->get(AgidBlocks::FIELD_PAGOPA_FISCAL_CODE)->getValue()['0']['value'];
  }

}
