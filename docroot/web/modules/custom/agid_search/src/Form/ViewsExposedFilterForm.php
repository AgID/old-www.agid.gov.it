<?php

namespace Drupal\agid_search\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Form\ViewsExposedForm;

/**
 * Class ViewsExposedFilterForm.
 *
 * This class removes the selected form elements and replaces them with
 * hidden form elements in order to keep any other forms inputs.
 *
 * @package Drupal\agid_search\Form
 */
class ViewsExposedFilterForm extends ViewsExposedForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $exposed_filters_disable = $form_state->get('exposed_filters_disable');

    // Reactive filter selected.
    foreach ($exposed_filters_disable as $filter_id) {
      if (isset($form[$filter_id])) {

        // Hide the element interested.
        $form[$filter_id]['#attributes']['class'][] = 'visually-hidden';
        $form[$filter_id]['#prefix'] = '<div class="visually-hidden">';
        $form[$filter_id]['#suffix'] = '</div>';
      }
    }

    return $form;
  }

}
