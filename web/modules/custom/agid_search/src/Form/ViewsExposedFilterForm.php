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

    /** @var \Drupal\views\ViewExecutable $view */
    $view = $form_state->get('view');
    // @todo: to consider also the initial values of the elements of the form, eg items_for_page.
    $exposed_input = $view->getExposedInput();

    // Reactive filter selected.
    foreach ($exposed_filters_disable as $filter_id) {
      if (isset($form[$filter_id])) {

        // Store old element and remove it.
        $old_element = $form[$filter_id];
        unset($form[$filter_id]);

        // Create a new element form (hidden).
        // It is necessary to recreate the element for the form GET.
        if (!$old_element['#multiple']) {
          $form[$filter_id] = [
            '#type' => 'hidden',
            '#value' => isset($exposed_input[$filter_id]) ? $exposed_input[$filter_id] : $old_element['#default_value'],
          ];
        }
        else {
          // In the case of multiple elements how checkboxes.
          foreach ($exposed_input[$filter_id] as $name => $input) {
            $form[$filter_id][$name] = [
              '#type' => 'hidden',
              '#value' => $input,
              '#name' => "{$filter_id}[{$name}]",
            ];
          }
        }

      }
    }

    return $form;
  }

}
