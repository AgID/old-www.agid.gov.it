<?php

  namespace Drupal\agid_repo_widget\Plugin\Field\FieldWidget;

  use Drupal\Core\Url;
  use Drupal\entity_browser\Plugin\Field\FieldWidget\FileBrowserWidget;

  /**
   * Widget for repository files field
   *
   * @FieldWidget(
   *   id = "repo_files_widget",
   *   label = @Translation("Repository Files Widget"),
   *   field_types = {
   *     "entity_reference"
   *   },
   *   multiple_values = TRUE,
   * )
   */
  class RepoFilesWidget extends FileBrowserWidget {
    // Display Table
    protected $displayTable = [];

    public function __construct($plugin_id, $plugin_definition, \Drupal\Core\Field\FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager, \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher, \Drupal\entity_browser\FieldWidgetDisplayManager $field_display_manager, \Drupal\Core\Config\ConfigFactoryInterface $config_factory, \Drupal\Core\Entity\EntityDisplayRepositoryInterface $display_repository, \Drupal\Core\Extension\ModuleHandlerInterface $module_handler, \Drupal\Core\Session\AccountInterface $current_user) {
      parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings, $entity_type_manager, $event_dispatcher, $field_display_manager, $config_factory, $display_repository, $module_handler, $current_user);
    }

    public static function defaultSettings() {
      return parent::defaultSettings();
    }

    /**
     * Implmenets a different displayCurrentSelection function
     *
     * @param $details_id
     * @param $field_parents
     * @param $entities
     *
     * @return array
     * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
     */
    protected function displayCurrentSelection($details_id, $field_parents, $entities) {
      $field_machine_name = $this->fieldDefinition->getName();
      $order_class = $field_machine_name . '-delta-order';
      // Get prepared table
      $table = $this->prepareTable($order_class);

      // Loop on all entities
     foreach ($entities as $delta => $entity) {
       // Entity's id
       $entity_id = $entity->id();

       // Enttiy's title
       $title = $entity->label();
       $title = isset($title) ? mb_strimwidth($title, 0, 40, '...') : null;

       // Get file's name, created date and file's typology.
       $filename = mb_strwidth(reset($entity->filename->getValue())['value'], 0, 40, '...');
       $typology = reset($entity->field_type->getValue())['target_id'];
       $typeObject = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($typology);
       $typeLabel = !is_null($typeObject) ? $typeObject->label() : $this->t('No typology specified');

       // Create table's row
       $table[$entity_id] = [
         '#attributes' => [
           'class' => ['draggable'],
           'data-entity-id' => $entity->getEntityTypeId() . ':' . $entity_id,
           'data-row-id' => $delta,
         ],
         'title-filename' => [
           '#type' => 'container',
           'items' => [
             'title' => ['#markup' => isset($title) ? $title : $filename],
           ]
         ],
         'typology' => [ '#type' => 'item', '#markup' => $typeLabel ],
         'edit_button' => [
           '#type' => 'submit',
           '#value' => $this->t('Edit'),
           '#ajax' => [
             'url' => Url::fromRoute('entity_browser.edit_form', ['entity_type' => $entity->getEntityTypeId(), 'entity' => $entity_id]),
             'options' => ['query' => ['details_id' => $details_id]],
           ],
           '#attributes' => [
             'data-entity-id' => $entity->getEntityTypeId() . ':' . $entity_id,
             'data-row-id' => $delta,
           ],
         ],
         'remove_button' => [
           '#type' => 'submit',
           '#value' => $this->t('Remove'),
           '#ajax' => [
             'callback' => [get_class($this), 'updateWidgetCallback'],
             'wrapper' => $details_id,
           ],
           '#submit' => [[get_class($this), 'removeItemSubmit']],
           '#name' => $field_machine_name . '_remove_' . $entity_id . '_' . md5(json_encode($field_parents)),
           '#limit_validation_errors' => [array_merge($field_parents, [$field_machine_name, 'target_id'])],
           '#attributes' => [
             'data-entity-id' => $entity->getEntityTypeId() . ':' . $entity_id,
             'data-row-id' => $delta,
           ],
         ],
         '_weight' => [
           '#type' => 'weight',
           '#title' => $this->t('Weight for row @number', ['@number' => $delta + 1]),
           '#title_display' => 'invisible',
           '#delta' => count($entities),
           '#default_value' => $delta,
           '#attributes' => ['class' => [$order_class]],
         ]
       ];
     }

     return $table;
    }

    private function prepareTable($order_class) {
      $table = [
        '#type' => 'table',
        '#empty' => $this->t('No files yet'),
        '#attributes' => ['class' => ['entities-list']],
        '#responsive' => true,
        '#sticky' => true,
        '#tabledrag' => array(
          [
            'action' => 'order',
            'relationship' => 'sibling',
            'group' => $order_class,
          ],
        ),
      ];

      // ... and set header's columns
      $table['#header'][] = ['data' => $this->t('Title & Filename'), 'field' => 1];
      $table['#header'][] = ['data' => $this->t('File\'s Typology'), 'field' => 2];
      $table['#header'][] = ['data' => $this->t('Operations'), 'field' => 3, 'colspan' => 2];
      $table['#header'][] = $this->t('Order', [], ['context' => 'Sort order']);
      // Attach entity_browser/file_browser library to table
      $table['#attached']['library'][] = 'entity_browser/file_browser';
      return $table;
    }
  }

