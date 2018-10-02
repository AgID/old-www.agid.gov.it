<?php

namespace Drupal\agid_search\Plugin\views\pager;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\pager\Full;

/**
 * Class FullRadios
 *
 * @ViewsPager(
 *   id = "full_radios",
 *   title = @Translation("Paged output, full pager but use how widget the radio buttons"),
 *   short_title = @Translation("Full with radios"),
 *   help = @Translation("Paged output, full Drupal style but use how widget the radio buttons"),
 *   theme = "pager",
 *   register_theme = FALSE
 * )
 *
 * @package Drupal\agid_search\Plugin\views\pager
 */
class FullRadios extends Full {

  /**
   * {@inheritdoc}
   */
  public function exposedFormAlter(&$form, FormStateInterface $form_state) {
    parent::exposedFormAlter($form, $form_state);
    if (isset($form['items_per_page'])) {
      $form['items_per_page']['#type'] = 'radios';
    }
  }

}
