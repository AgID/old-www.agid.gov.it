<?php
  /**
   * @file
   * Contains Drupal\webform_multistep\Form\MultistepAdminForm.
   */
  namespace Drupal\webform_multistep\Form;

  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;

  class MultistepAdminForm extends ConfigFormBase {
    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
      return [
        'webform_multistep.adminsettings',
      ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
      return 'webform_multistep';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
      $config = $this->config('webform_multistep.adminsettings');

      $webform_ref = $config->get('webform_reference');
      if (isset($webform_ref) && !is_array($webform_ref))
        $webform_ref = \Drupal::entityTypeManager()->getStorage('webform')->load($webform_ref);
      else {
        if (is_array($webform_ref)) {
          $temp = [];
          foreach ($webform_ref as $wf_id) {
            $temp[] = \Drupal::entityTypeManager()->getStorage('webform')->load($wf_id['target_id']);
          }
          $webform_ref = $temp;
        }
      }
      // Entity Autocomplete Reference to Webform entities
      $form['webform_reference'] = array(
        '#type' => 'entity_autocomplete',
        '#title' => $this->t('Webform'),
        '#target_type' => 'webform',
        '#tags' => TRUE,
        '#default_value' => $webform_ref,
      );

      $form['pdf_options'] = array(
        '#type' => 'details',
        '#title' => $this->t('PDF Settings')
      );
      $form['pdf_options']['pdf_filename'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('PDF File name'),
        '#default_value' => $config->get('pdf_filename'),
        '#description' => $this->t('PDF\'s filename. It\'s possible to use a % character that will be automatically replaced with a numeric value. So %_example.pdf will be 09_example.pdf.')
      );
      $form['pdf_options']['pdf_filepath'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('PDF File Uri'),
        '#description' => $this->t('Ex: public://webforms, this will save file into /sites/default/files/webforms'),
        '#default_value' => $config->get('pdf_filepath'),
      );
      $form['pdf_options']['pdf_header'] = array(
        '#type' => 'text_format',
        '#title' => $this->t('PDF Header'),
        '#format' => 'full_html',
        '#allowed_formats' => array('full_html', 'filtered_text'),
        '#default_value' => $config->get('pdf_header')['value'],
      );
      $form['pdf_options']['pdf_footer'] = array(
        '#type' => 'text_format',
        '#title' => $this->t('PDF Footer'),
        '#format' => 'full_html',
        '#allowed_formats' => array('full_html', 'filtered_text'),
        '#default_value' => $config->get('pdf_footer')['value'],
      );

      return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      parent::submitForm($form, $form_state);

      kint($form_state->getValue('webform_reference'));
      $this->config('webform_multistep.adminsettings')
           ->set('webform_reference', $form_state->getValue('webform_reference'))
           ->set('pdf_filepath', $form_state->getValue('pdf_filepath'))
           ->set('pdf_filename', $form_state->getValue('pdf_filename'))
           ->set('pdf_header', $form_state->getValue('pdf_header'))
           ->set('pdf_footer', $form_state->getValue('pdf_footer'))
           ->save();
    }
  }
