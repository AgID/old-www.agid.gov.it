<?php

namespace Drupal\agid_search\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ForceIndexingForm
 *
 * @package Drupal\agid_search\Form
 */
class ForceIndexingForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'agid_search_force_indexing_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Content.
    $form['nodes'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Nodes'),
    ];
    $form['nodes']['#tree'] = TRUE;
    $form['nodes']['entities'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Nodes'),
      '#target_type' => 'node',
      '#tags' => TRUE,
    ];
    $form['nodes']['index_directly'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Index Directly'),
    ];
    $form['nodes']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Re-index nodes'),
      '#submit' => [[$this, 'submitFormNodes']],
    ];

    // Files.
    $form['files'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Files'),
    ];
    $form['files']['#tree'] = TRUE;
    $form['files']['entities'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('File'),
      '#target_type' => 'file',
      '#tags' => TRUE,
    ];
    $form['files']['index_directly'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Index Directly'),
    ];
    $form['files']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Re-index files'),
      '#submit' => [[$this, 'submitFormFiles']],
    ];
    return $form;
  }

  /**
   * Custom submission for nodes.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function submitFormNodes(array &$form, FormStateInterface $form_state) {
    $data = $form_state->getValue('nodes');
    $entities_id = array_column($data['entities'], 'target_id');
    $index_directly = $data['index_directly'];
    // Batch.
    $this->batch('node', $entities_id, $index_directly);
  }

  /**
   * Custom submission for files.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function submitFormFiles(array &$form, FormStateInterface $form_state) {
    $data = $form_state->getValue('files');
    $entities_id = array_column($data['entities'], 'target_id');
    $index_directly = $data['index_directly'];
    // Batch.
    $this->batch('file', $entities_id, $index_directly);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Nothing.
  }

  /**
   * Batch processing for index product variations.
   *
   * @param $entities_id
   * @param $index_directly
   */
  protected function batch($entity_type, $entities_id, $index_directly) {
    $batch = [
      'title' => t('Index Contents...'),
      'operations' => [
        [
          '\Drupal\agid_search\IndexEntities::indexEntities',
          [$entity_type, $entities_id, $index_directly],
        ],
      ],
      'finished' => '\Drupal\agid_search\IndexEntities::indexEntitiesFinishedCallback',
    ];
    batch_set($batch);
  }
}
