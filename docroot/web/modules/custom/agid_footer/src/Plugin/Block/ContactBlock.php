<?php

namespace Drupal\agid_footer\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'ContactBlock' block.
 *
 * @Block(
 *  id = "contact_block",
 *  admin_label = @Translation("Contact block"),
 * )
 */
class ContactBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['url_link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL link titolo'),
      '#description' => $this->t('URL a cui deve puntare il link del titolo del blocco. Se lasciato vuoto, il link non viene mostrato.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => isset($this->configuration['url_link']) ? $this->configuration['url_link'] : '',
    ];
    $form['indirizzo_riga_1'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Indirizzo (riga 1)'),
      '#description' => $this->t('Prima riga dell\'indirizzo di contatto dell\'Amministrazione (lasciare vuoto per non usare)'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => isset($this->configuration['indirizzo'][0]) ? $this->configuration['indirizzo'][0] : '',
    ];
    $form['indirizzo_riga_2'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Indirizzo (riga 2)'),
      '#description' => $this->t('Seconda riga dell\'indirizzo di contatto dell\'Amministrazione (lasciare vuoto per non usare)'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => isset($this->configuration['indirizzo'][1]) ? $this->configuration['indirizzo'][1] : '',
    ];
    $form['indirizzo_riga_3'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Indirizzo (riga 3)'),
      '#description' => $this->t('Terza riga dell\'indirizzo di contatto dell\'Amministrazione (lasciare vuoto per non usare)'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => isset($this->configuration['indirizzo'][2]) ? $this->configuration['indirizzo'][2] : '',
    ];
    $form['indirizzo_riga_4'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Indirizzo (riga 4)'),
      '#description' => $this->t('Quarta riga dell\'indirizzo di contatto dell\'Amministrazione (lasciare vuoto per non usare)'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => isset($this->configuration['indirizzo'][3]) ? $this->configuration['indirizzo'][3] : '',
    ];
    $form['codice_fiscale'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Codice fiscale'),
      '#description' => $this->t('Codice fiscale dell\'Amministrazione (lasciare vuoto per non usare)'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => isset($this->configuration['codice_fiscale']) ? $this->configuration['codice_fiscale'] : '',
    ];
    $form['pec'] = [
      '#type' => 'textfield',
      '#title' => $this->t('PEC'),
      '#description' => $this->t('Indirizzo di posta elettronica certificata dell\'Amministrazione (lasciare vuoto per non usare)'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => isset($this->configuration['pec']) ? $this->configuration['pec'] : '',
    ];
    $form['press'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Contatti stampa'),
      '#description' => $this->t('Indirizzo di posta elettronica della stampa'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => isset($this->configuration['press']) ? $this->configuration['press'] : '',
    ];
    $form['url_scrivici'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL link scrivici'),
      '#description' => $this->t('URL a cui deve puntare il link \'Scrivici\' alla fine del blocco. Se lasciato vuoto il link non viene mostrato.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => isset($this->configuration['url_scrivici']) ? $this->configuration['url_scrivici'] : '',
    ];
    $form['url_gmaps'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL Google Maps'),
      '#description' => $this->t('URL per linkare la prima riga dell\'indirizzo a google maps. Se lasciato vuoto il link non viene mostrato.'),
      '#maxlength' => 400,
      '#size' => 64,
      '#default_value' => isset($this->configuration['url_gmaps']) ? $this->configuration['url_gmaps'] : '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['url_link'] = $form_state->getValue('url_link');
    $this->configuration['indirizzo'] = [];
    $this->configuration['indirizzo'][] = $form_state->getValue('indirizzo_riga_1');
    $this->configuration['indirizzo'][] = $form_state->getValue('indirizzo_riga_2');
    $this->configuration['indirizzo'][] = $form_state->getValue('indirizzo_riga_3');
    $this->configuration['indirizzo'][] = $form_state->getValue('indirizzo_riga_4');
    $this->configuration['codice_fiscale'] = $form_state->getValue('codice_fiscale');
    $this->configuration['pec'] = $form_state->getValue('pec');
    $this->configuration['press'] = $form_state->getValue('press');
    $this->configuration['url_scrivici'] = $form_state->getValue('url_scrivici');
    $this->configuration['url_gmaps'] = $form_state->getValue('url_gmaps');
  }


  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    $context = [];
    $template = "";
    if (!empty($this->configuration['url_link'])) {
      $template .= '<a class="Footer-bigLink Footer-contact-title" href="'. $this->configuration['url_link'] .'">Sede e contatti</a>';
    }

    foreach ($this->configuration['indirizzo'] as $index => $row) {
      if ($index == 0 && isset($this->configuration['url_gmaps'])) {
        $template .= '<p><a href="{{url_gmaps}}" target="new">{{row_' . $index . '}}</a></p>';
        $context["row_{$index}"] = $row;
        $context["url_gmaps"] = $this->configuration['url_gmaps'];
      } else {
        $template .= '<p>{{row_' . $index . '}}</p>';
        $context["row_{$index}"] = $row;
      }
    }

    if (isset($this->configuration['codice_fiscale'])) {
      $template .= '<p><strong>Codice fiscale: </strong>' . $this->configuration['codice_fiscale'] . '</p>';
    }

    if (isset($this->configuration['pec'])) {
      $template .= '<p><strong>PEC: </strong><a href="mailto:' . $this->configuration['pec'] . '">' . $this->configuration['pec'] . '</a></p>';
    }

    if (isset($this->configuration['press'])) {
      $template .= '<p><strong>Contatti stampa:</strong> <a href="mailto:' . $this->configuration['press'] . '">' . $this->configuration['press'] . '</a></p>';
    }

    if (!empty($this->configuration['url_scrivici'])) {
      $template .= '<a class="Footer-bigLink Footer-contact" href="' . $this->configuration['url_scrivici'] . '">Scrivici</a>';
    }

    $build['contact_block']  = [
      '#type' => 'inline_template',
      '#template' => $template,
      '#context' => $context
    ];

    return $build;
  }

}
