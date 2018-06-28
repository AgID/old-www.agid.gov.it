<?php

namespace Drupal\agid_search\Plugin\Block;

use Drupal\agid_search\Form\ViewsExposedFilterForm;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\SubformStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\views\ViewExecutableFactory;
use Drupal\views\Views;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBuilderInterface;

/**
 * Provides a separate views exposed filter block.
 *
 * @Block(
 *  id = "agid_search_views_exposed_filter_block",
 *  admin_label = @Translation("Views exposed filter in block"),
 * )
 */
class ViewsExposedFilterBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\Core\Form\FormBuilderInterface
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * View Storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $viewStorage;

  /**
   * View executable.
   *
   * @var \Drupal\views\ViewExecutableFactory
   */
  protected $viewExecutable;

  /**
   * Constructs a new SearchFiltersBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   * @param \Drupal\views\ViewExecutableFactory $view_executable
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, FormBuilderInterface $form_builder, ViewExecutableFactory $view_executable) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $form_builder;
    $this->viewStorage = $entity_type_manager->getStorage('view');
    $this->viewExecutable = $view_executable;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, $plugin_id, $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('form_builder'),
      $container->get('views.executable')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'view_display' => '',
      'exposed_filters_disable' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $complete_form_state = $form_state instanceof SubformStateInterface ? $form_state->getCompleteFormState() : $form_state;

    $form['view_display'] = [
      '#type' => 'select',
      '#options' => Views::getViewsAsOptions(FALSE, 'enabled'),
      '#title' => $this->t('View & Display'),
      '#default_value' => $this->configuration['view_display'],
      '#required' => TRUE,
      '#ajax' => [
        'callback' => [$this, 'loadOptions'],
        'wrapper' => 'agid_search_views_exposed_filter_block-options',
      ],
    ];
    $form['options'] = [
      '#type' => 'container',
      '#attributes' => [
        'id' => 'agid_search_views_exposed_filter_block-options',
      ],
    ];

    $view_display = $complete_form_state->getValue([
      'settings',
      'view_display',
    ], $this->configuration['view_display']);
    if (empty($view_display)) {
      return $form;
    }
    $this->configuration['view_display'] = $view_display;

    if ($view_executable = $this->getViewExecutable()) {
      $view_executable->initHandlers();

      $options = [];
      foreach ($view_executable->display_handler->handlers as $type => $value) {
        /** @var \Drupal\views\Plugin\views\ViewsHandlerInterface $handler */
        foreach ($view_executable->$type as $id => $handler) {
          if ($handler->canExpose() && $handler->isExposed()) {
            $options[$id] = $handler->adminLabel();
          }
        }
      }
      $options['items_per_page'] = $this->t('Items per page');

      $form['options']['exposed_filters_disable'] = [
        '#type' => 'checkboxes',
        '#options' => $options,
        '#title' => $this->t('Exposed filters disable'),
        '#default_value' => $this->configuration['exposed_filters_disable'],
      ];
    }

    return $form;
  }

  /**
   * Callback ajax for retrieve the options per exposed filters.
   */
  public function loadOptions($form, FormStateInterface $form_state) {
    return $form['settings']['options'];
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['view_display'] = $form_state->getValue('view_display');
    $this->configuration['exposed_filters_disable'] = $form_state->getValue([
      'options',
      'exposed_filters_disable',
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    if (empty($this->configuration['view_display'])) {
      return ['#markup' => $this->t("Please check the views exposed filter block configuration.")];
    }

    // Load view executable.
    if ($view_executable = $this->getViewExecutable()) {

      // Init handlers.
      $view_executable->initHandlers();

      // Create form state.
      $form_state = (new FormState())
        ->setStorage([
          'view' => $view_executable,
          'display' => &$view_executable->display_handler->display,
          'rerender' => TRUE,
          // Custom data.
          'exposed_filters_disable' => array_filter($this->configuration['exposed_filters_disable']),
        ])
        ->setMethod('get')
        ->setAlwaysProcess()
        ->disableRedirect();
      $form_state->set('rerender', NULL);

      // Build the form.
      return $this->formBuilder->buildForm(ViewsExposedFilterForm::class, $form_state);
    }
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $view = $this->getViewExecutable();
    $contexts = $view ? $view->display_handler->getCacheMetadata()
      ->getCacheContexts() : [];
    return Cache::mergeContexts(parent::getCacheContexts(), $contexts);
  }

  /**
   * Retrieve a view executable.
   *
   * @return \Drupal\views\ViewExecutable|null
   */
  protected function getViewExecutable() {
    if (empty($this->configuration['view_display'])) {
      return NULL;
    }
    list($view_id, $display_id) = explode(':', $this->configuration['view_display']);
    /** @var \Drupal\views\ViewEntityInterface|NULL $view */
    $view = $this->viewStorage->load($view_id);
    if ($view) {
      $view_executable = $this->viewExecutable->get($view);
      $view_executable->setDisplay($display_id);
      return $view_executable;
    }
    return NULL;
  }


}
