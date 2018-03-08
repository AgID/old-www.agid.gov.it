<?php

namespace Drupal\agid_blocks;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\views\ViewExecutableFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\Cache;

/**
 * Provides a basic class for Agid related content blocks.
 *
 *
 */
abstract class AgidRelatedContentBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
  protected $taxonomy_ids;

  /**
   * The value for the current node.
   *
   * @var \Drupal\node\NodeInterface $node
   */
  protected $node;

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

    $this->node = $this->currentRouteMatch->getParameter('node');
    $this->taxonomy_ids = $this->retrieveDataFromRoute();

    if ( is_null($this->taxonomy_ids) ) {
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
  public function getCacheTags() {
    // With this when your node change your block will rebuild.
    if ($this->node) {
      // If there is node add its cachetag.
      return Cache::mergeTags(parent::getCacheTags(), ['node:' . $this->node->id()]);
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
    $node = $this->node;

    if (empty($node)) {
      return;
    }

    if (!key_exists($node->bundle(), AgidBlocks::VIEW_RELATED_CONTENT_ALLOWED)) {
      return;
    }

    $field_name = AgidBlocks::VIEW_RELATED_CONTENT_ALLOWED[$node->bundle()];

    if (empty($node->get($field_name)) || empty($node->get($field_name)->getValue())) {
      return;
    }

    $term_ids = null;
    for ($i = 0; isset($node->get($field_name)->getValue()[$i]); $i++) {
      $term_ids .= $node->get($field_name)->getValue()[$i]['target_id'] . "+";
    }

    $term_ids = substr($term_ids, 0, -1);

    return $term_ids;
  }

}
