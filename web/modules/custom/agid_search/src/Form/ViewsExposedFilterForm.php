<?php

namespace Drupal\agid_search\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Form\ViewsExposedForm;

/**
 * Class ViewsExposedFilterForm.
 *
 * This class is used only update the form.
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
        // TODO: not work:
        // We can not remove the filter but only hide it,
        // otherwise in the request to send it will not use any filters
        // used by other forms on the page.
         hide($form[$filter_id]);
      }
    }

    return $form;
  }

}
