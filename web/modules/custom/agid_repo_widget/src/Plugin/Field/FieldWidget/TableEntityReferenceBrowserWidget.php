<?php

namespace Drupal\agid_repo_widget\Plugin\Field\FieldWidget;

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
 *   description = @Translation("Uses entity table browser to select
 *   entities."), field_types = {
 *     "entity_reference"
 *   },
 *   multiple_values = TRUE,
 * )
 */
class TableEntityReferenceBrowserWidget extends EntityReferenceBrowserWidget {

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
    $build = [
      '#type' => 'table',
      '#header' => [
        $this->t('Title & Filename'),
        $this->t('File\'s Typology'),
        $this->t('Operations'),
      ],
      '#empty' => $this->t('No files yet'),
      '#attributes' => ['class' => ['entities-list']],
      '#responsive' => TRUE,
      '#rows' => [],
    ];
    $row_id = 0;
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
        $edit_button_access &= $this->moduleHandler->moduleExists('file_entity');
      }
      $build[$entity->id()]['#attributes'] = [
        'data-entity-id' => $entity->getEntityTypeId() . ':' . $entity->id(),
        'data-row-id' => $row_id,
      ];

      // Get entity's title.
      $title = $entity->label() !== NULL ? $entity->label() : $entity->getFilename();
      $title = mb_strimwidth($title, 0, 40, '...');
      $build[$entity->id()]['title'] = [
        '#markup' => $title,
      ];

      // Get file's typology.
      $type_label = $this->t('No typology specified');
      if ($entity->hasField('field_type')
        && !$entity->get('field_type')->isEmpty()) {
        /** @var \Drupal\taxonomy\TermInterface $term */
        $term = $entity->get('field_type')->entity;
        $type_label = $term->label();
      }
      $build[$entity->id()]['type'] = [
        '#markup' => $type_label,
      ];;

      // Operation
      $build[$entity->id()]['operations']['edit_button'] = [
        '#type' => 'submit',
        '#value' => $this->t('Edit'),
        '#ajax' => [
          'url' => Url::fromRoute(
            'entity_browser.edit_form', [
              'entity_type' => $entity->getEntityTypeId(),
              'entity' => $entity->id(),
            ]
          ),
          'options' => [
            'query' => [
              'details_id' => $details_id,
            ],
          ],
        ],
        '#access' => $edit_button_access,
      ];
      $build[$entity->id()]['operations']['remove_button'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#ajax' => [
          'callback' => [get_class($this), 'updateWidgetCallback'],
          'wrapper' => $details_id,
        ],
        '#submit' => [[get_class($this), 'removeItemSubmit']],
        '#name' => $this->fieldDefinition->getName() . '_remove_' . $entity->id() . '_' . $row_id . '_' . md5(json_encode($field_parents)),
        '#limit_validation_errors' => [array_merge($field_parents, [$this->fieldDefinition->getName()])],
        '#attributes' => [
          'data-entity-id' => $entity->getEntityTypeId() . ':' . $entity->id(),
          'data-row-id' => $row_id,
        ],
        '#access' => (bool) $this->getSetting('field_widget_remove'),
      ];
      $row_id++;
    }
    return $build;
  }

}
