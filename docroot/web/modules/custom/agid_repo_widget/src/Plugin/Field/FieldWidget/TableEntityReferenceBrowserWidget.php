<?php

namespace Drupal\agid_repo_widget\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\SortArray;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\entity_browser\Plugin\Field\FieldWidget\EntityReferenceBrowserWidget;
use Drupal\file\FileInterface;

/**
 * Widget used for repository files field.
 *
 * Differs from the parent class for presentation in the entity table.
 *
 * @FieldWidget(
 *   id = "agid_repo_widget_table_browser_entity_reference",
 *   label = @Translation("Table Entity browser"),
 *   description = @Translation("Uses entity table browser to select entities."),
 *   field_types = {
 *     "entity_reference"
 *   },
 *   multiple_values = TRUE,
 * )
 */
class TableEntityReferenceBrowserWidget extends EntityReferenceBrowserWidget {

  /**
   * Due to the table structure, this widget has a different depth.
   *
   * @var int
   */
  protected static $deleteDepth = 3;

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);
    $element['field_widget_display']['#access'] = FALSE;
    $element['field_widget_display_settings']['#access'] = FALSE;
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  protected function displayCurrentSelection($details_id, $field_parents, $entities) {
    $field_machine_name = $this->fieldDefinition->getName();
    $widget_settings = $this->getSettings();
    $has_file_entity = $this->moduleHandler->moduleExists('file_entity');
    $order_class = $field_machine_name . '-delta-order';
    $delta = 0;

    // Table.
    $current = [
      '#type' => 'table',
      '#empty' => $this->t('No files yet'),
      '#attributes' => ['class' => ['entities-list']],
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => $order_class,
        ],
      ],
    ];

    // Add the header columns.
    $current['#header'][] = $this->t('Title & Filename');
    $current['#header'][] = $this->t('File\'s Typology');
    $current['#header'][] = ['data' => $this->t('Operations'), 'colspan' => 2];
    $current['#header'][] = $this->t('Order', [], ['context' => 'Sort order']);

    /** @var \Drupal\file\FileInterface[] $entities */
    foreach ($entities as $entity) {

      if (!$entity instanceof FileInterface) {
        // Exclude not file entities.
        continue;
      }

      $edit_button_access = $this->getSetting('field_widget_edit') && $entity->access('update', $this->currentUser);
      if ($entity->getEntityTypeId() == 'file') {
        // On file entities, the "edit" button shouldn't be visible unless
        // the module "file_entity" is present, which will allow them to be
        // edited on their own form.
        $edit_button_access &= $has_file_entity;
        // Check to see if this entity has an edit form. If not, the edit button
        // will only throw an exception.
        $edit_button_access &= !empty($entity->getEntityType()
          ->getFormClass('edit'));
      }

      // Get entity id.
      $entity_id = $entity->id();

      $current[$entity_id] = [
        '#attributes' => [
          'class' => ['draggable'],
          'data-entity-id' => $entity->getEntityTypeId() . ':' . $entity_id,
          'data-row-id' => $delta,
        ],
      ];

      // Get entity's title.
      $title = $entity->label() !== NULL ? $entity->label() : $entity->getFilename();
      $title = mb_strimwidth($title, 0, 40, '...');

      // Get file's typology.
      $type_label = $this->t('No typology specified');
      if ($entity->hasField('field_type')
        && !$entity->get('field_type')->isEmpty()) {
        /** @var \Drupal\taxonomy\TermInterface $term */
        $term = $entity->get('field_type')->entity;
        $type_label = $term->label();
      }

      // Row.
      $current[$entity_id] += [
        'title' => [
          '#markup' => $title,
        ],
        'type' => [
          '#markup' => $type_label,
        ],
        'edit_button' => [
          '#type' => 'submit',
          '#value' => $this->t('Edit'),
          '#ajax' => [
            'url' => Url::fromRoute('entity_browser.edit_form', [
              'entity_type' => $entity->getEntityTypeId(),
              'entity' => $entity_id,
            ]),
            'options' => ['query' => ['details_id' => $details_id]],
          ],
          '#attributes' => [
            'data-entity-id' => $entity->getEntityTypeId() . ':' . $entity->id(),
            'data-row-id' => $delta,
          ],
          '#access' => $edit_button_access,
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
          '#limit_validation_errors' => [
            array_merge($field_parents, [
              $field_machine_name,
              'target_id',
            ]),
          ],
          '#attributes' => [
            'data-entity-id' => $entity->getEntityTypeId() . ':' . $entity->id(),
            'data-row-id' => $delta,
          ],
          '#access' => (bool) $widget_settings['field_widget_remove'],
        ],
        '_weight' => [
          '#type' => 'weight',
          '#title' => $this->t('Weight for row @number', ['@number' => $delta + 1]),
          '#title_display' => 'invisible',
          // Note: this 'delta' is the FAPI #type 'weight' element's property.
          '#delta' => count($entities),
          '#default_value' => $delta,
          '#attributes' => ['class' => [$order_class]],
        ],
      ];

      $current['#attached']['library'][] = 'entity_browser/file_browser';

      $delta++;
    }

    return $current;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    $ids = empty($values['target_id']) ? [] : explode(' ', trim($values['target_id']));
    $return = [];
    foreach ($ids as $id) {
      $id = explode(':', $id)[1];
      if (is_array($values['current']) && isset($values['current'][$id])) {
        $item_values = [
          'target_id' => $id,
          '_weight' => $values['current'][$id]['_weight'],
        ];
        $return[] = $item_values;
      }
    }

    // Return ourself as the structure doesn't match the default.
    usort($return, function ($a, $b) {
      return SortArray::sortByKeyInt($a, $b, '_weight');
    });

    return array_values($return);
  }

}
