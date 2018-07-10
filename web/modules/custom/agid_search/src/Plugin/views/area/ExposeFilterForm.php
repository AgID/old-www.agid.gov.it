<?php

namespace Drupal\agid_search\Plugin\views\area;

use Drupal\agid_search\Form\ViewsExposedFilterForm;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\area\AreaPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an area for print expose filter form.
 *
 * @ingroup views_area_handlers
 *
 * @ViewsArea("agid_search_expose_filter_form")
 */
class ExposeFilterForm extends AreaPluginBase {

  /**
   * Drupal\Core\Form\FormBuilderInterface
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * SpellCheckSolr constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $form_builder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $this->view->initHandlers();
    $options = [];
    foreach ($this->view->display_handler->handlers as $type => $value) {
      /** @var \Drupal\views\Plugin\views\ViewsHandlerInterface $handler */
      foreach ($this->view->$type as $id => $handler) {
        if ($handler->canExpose() && $handler->isExposed()) {
          $options[$id] = $handler->adminLabel();
        }
      }
    }
    $options['items_per_page'] = $this->t('Items per page');
    $form['exposed_filters_disable'] = [
      '#type' => 'checkboxes',
      '#options' => $options,
      '#title' => $this->t('Exposed Filters disable'),
      '#default_value' => $this->options['exposed_filters_disable'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Nothing.
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Core\Form\EnforcedResponseException
   * @throws \Drupal\Core\Form\FormAjaxException
   */
  public function render($empty = FALSE) {
    return $this->getExposedFilterForm();
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['exposed_filters_disable']['default'] = [];
    return $options;
  }

  /**
   * Get exposed filter form.
   *
   * @return array
   *
   * @throws \Drupal\Core\Form\EnforcedResponseException
   * @throws \Drupal\Core\Form\FormAjaxException
   */
  protected function getExposedFilterForm() {
    // Init handlers.
    $this->view->initHandlers();

    // Create form state.
    $form_state = (new FormState())
      ->setStorage([
        'view' => $this->view,
        'display' => &$this->view->display_handler->display,
        'rerender' => TRUE,
        // Custom data.
        'exposed_filters_disable' => array_filter($this->options['exposed_filters_disable']),
        'views_area' => $this->pluginId,
      ])
      ->setMethod('get')
      ->setAlwaysProcess()
      ->disableRedirect();
    $form_state->set('rerender', NULL);

    // Build the form.
    return $this->formBuilder
      ->buildForm(ViewsExposedFilterForm::class, $form_state);
  }

}
