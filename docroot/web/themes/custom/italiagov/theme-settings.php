<?php

/**
 * @file
 * Add custom theme settings to the ZURB Foundation theme.
 */

use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_FORM_ID_alter().
 */
function italiagov_form_system_theme_settings_alter(&$form, FormStateInterface &$form_state, $form_id = NULL) {
  // Work-around for a core bug affecting admin themes. See issue #943212.
  if (isset($form_id)) {
    return;
  }

  $form['#tree'] = TRUE;

  // Header settings.
  $form['header_settings'] = [
    '#type' => 'details',
    '#title' => t('Header'),
    '#description' => t('Customizable settings for site header.'),
    '#open' => TRUE,
  ];
  $form['header_settings']['italiagov_afferent_administration_name'] = [
    '#type' => 'textfield',
    '#title' => t('Afferent Administration Name'),
    '#description' => t('Here you can customize the afferent Administration name in the site header.'),
    '#default_value' => theme_get_setting('header_settings.italiagov_afferent_administration_name'),
  ];
  $form['header_settings']['italiagov_afferent_administration_url'] = [
    '#type' => 'url',
    '#title' => t('Afferent Administration URL'),
    '#description' => t('Here you can customize the afferent Administration URL in the site header. Insert a full absolute url like http://www.example.com.'),
    '#default_value' => theme_get_setting('header_settings.italiagov_afferent_administration_url'),
  ];
  $form['header_settings']['italiagov_public_administration_name'] = [
    '#type' => 'textarea',
    '#title' => t('Public administration name'),
    '#description' => t('Here you can customize the public administration name in the site header near the logo.'),
    '#default_value' => theme_get_setting('header_settings.italiagov_public_administration_name'),
  ];

  // Footer settings.
  $form['footer_settings'] = [
    '#type' => 'details',
    '#title' => t('Footer'),
    '#description' => t('Customizable settings for site footer.'),
    '#open' => TRUE,
  ];
  $form['footer_settings']['italiagov_footer_administration_name'] = [
    '#type' => 'textfield',
    '#title' => t('Public administration name'),
    '#description' => t('Set the Administration name as you would like to be printed in the site footer.'),
    '#default_value' => theme_get_setting('footer_settings.italiagov_footer_administration_name'),
  ];
  $form['footer_settings']['footer_top_title'] = [
    '#type' => 'textfield',
    '#title' => t('Title of footer-top region'),
    '#description' => t('Insert a title of the region, an empty value will not print the heading'),
    '#default_value' => theme_get_setting('footer_settings.footer_top_title'),
  ];
  $form['footer_settings']['footer_middle_title'] = [
    '#type' => 'textfield',
    '#title' => t('Title of footer-middle region'),
    '#description' => t('Insert a title of the region, an empty value will not print the heading'),
    '#default_value' => theme_get_setting('footer_settings.footer_middle_title'),
  ];

  // Social links.
  $form['social_settings'] = [
    '#type' => 'details',
    '#title' => t('Social links'),
    '#description' => t('Links to the social networks. These links will be printed with the relative icons in the site header and footer. Insert a full absolute url like http://www.facebook.com.'),
    '#open' => TRUE,
  ];
  $form['social_settings']['italiagov_socials_title'] = [
    '#type' => 'textfield',
    '#title' => t('Title of social links blocks'),
    '#description' => t('Insert a title of socials blocks.'),
    '#default_value' => theme_get_setting('social_settings.italiagov_socials_title'),
  ];
  $form['social_settings']['italiagov_socials_facebook'] = [
    '#type' => 'url',
    '#title' => t('Facebook'),
    '#description' => t('Insert the link to your Facebook page.'),
    '#default_value' => theme_get_setting('social_settings.italiagov_socials_facebook'),
  ];
  $form['social_settings']['italiagov_socials_twitter'] = [
    '#type' => 'url',
    '#title' => t('Twitter'),
    '#description' => t('Insert the link to your Twitter page.'),
    '#default_value' => theme_get_setting('social_settings.italiagov_socials_twitter'),
  ];
  $form['social_settings']['italiagov_socials_youtube'] = [
    '#type' => 'url',
    '#title' => t('Youtube'),
    '#description' => t('Insert the link to your YouTube channel.'),
    '#default_value' => theme_get_setting('social_settings.italiagov_socials_youtube'),
  ];
  $form['social_settings']['italiagov_socials_instagram'] = [
    '#type' => 'textfield',
    '#title' => t('Instagram'),
    '#description' => t('Insert the link to your Instagram page.'),
    '#default_value' => theme_get_setting('social_settings.italiagov_socials_instagram'),
  ];
  $form['social_settings']['italiagov_socials_medium'] = [
    '#type' => 'textfield',
    '#title' => t('Medium'),
    '#description' => t('Insert the link to your Medium page.'),
    '#default_value' => theme_get_setting('social_settings.italiagov_socials_medium'),
  ];

  // Social links location.
  $form['social_settings']['italiagov_socials_locations'] = [
    '#type' => 'checkboxes',
    '#title' => t('Location of social links'),
    '#description' => t('Select one or more regions where to show social links'),
    '#options' => [
      'header' => t('Header'),
      'footer_top_right' => t('Footer top right'),
      'footer_middle' => t('Footer middle'),
    ],
    '#default_value' => theme_get_setting('social_settings.italiagov_socials_locations'),
  ];

  // Advanced region settings.
  $form['advanced_region_settings'] = [
    '#type' => 'details',
    '#title' => t('Advanced regions settings'),
    '#description' => t('Theme advanced regions settings. Here you can add one or more
      class to the outer wrapper of each region. This can be useful for example to change
      the background color of a specific region'),
    '#open' => FALSE,
  ];
  $form['advanced_region_settings']['italiagov_region_classes_hero'] = [
    '#type' => 'textfield',
    '#title' => t('"Hero" region classes'),
    '#description' => t('Add one or more class to the hero region. Separate multiple classes using one space.'),
    '#default_value' => theme_get_setting('advanced_region_settings.italiagov_region_classes_hero'),
  ];
  $form['advanced_region_settings']['italiagov_region_classes_postscript_first'] = [
    '#type' => 'textfield',
    '#title' => t('"Postscript first" region classes'),
    '#description' => t('Add one or more class to the postscript first region. Separate multiple classes using one space.'),
    '#default_value' => theme_get_setting('advanced_region_settings.italiagov_region_classes_postscript_first'),
  ];
  $form['advanced_region_settings']['italiagov_region_classes_postscript_second'] = [
    '#type' => 'textfield',
    '#title' => t('"Postscript second" region classes'),
    '#description' => t('Add one or more class to the postscript second region. Separate multiple classes using one space.'),
    '#default_value' => theme_get_setting('advanced_region_settings.italiagov_region_classes_postscript_second'),
  ];
  $form['advanced_region_settings']['italiagov_region_classes_postscript_third'] = [
    '#type' => 'textfield',
    '#title' => t('"Postscript third" region classes'),
    '#description' => t('Add one or more class to the postscript third region. Separate multiple classes using one space.'),
    '#default_value' => theme_get_setting('advanced_region_settings.italiagov_region_classes_postscript_third'),
  ];
  $form['advanced_region_settings']['italiagov_region_classes_postscript_fourth'] = [
    '#type' => 'textfield',
    '#title' => t('"Postscript fourth" region classes'),
    '#description' => t('Add one or more class to the postscript fourth region. Separate multiple classes using one space.'),
    '#default_value' => theme_get_setting('advanced_region_settings.italiagov_region_classes_postscript_fourth'),
  ];
  $form['advanced_region_settings']['italiagov_region_classes_leads'] = [
    '#type' => 'textfield',
    '#title' => t('"Leads" region classes'),
    '#description' => t('Add one or more class to the leads region. Separate multiple classes using one space.'),
    '#default_value' => theme_get_setting('advanced_region_settings.italiagov_region_classes_leads'),
  ];
  $form['advanced_region_settings']['italiagov_region_classes_pre_footer'] = [
    '#type' => 'textfield',
    '#title' => t('"Pre footer" region classes'),
    '#description' => t('Add one or more class to the Pre footer fourth region. Separate multiple classes using one space.'),
    '#default_value' => theme_get_setting('advanced_region_settings.italiagov_region_classes_pre_footer'),
  ];

  // Error page 404 and 403.
  italiagov_error_page_settings($form, [404, 403]);
}

/**
 * Utility function to build settings section of error pages.
 *
 * @param array $form
 *   The settings form structured in a array.
 * @param array $error_types
 *   The list of error codes.
 */
function italiagov_error_page_settings(array &$form, array $error_types) {
  $err_settings = [];

  // Error settings section.
  $form['error_page_settings'] = [
    '#type' => 'details',
    '#title' => t('Error pages'),
    '#description' => t('Settings of the system error pages.'),
    '#open' => TRUE,
  ];

  foreach ($error_types as $error_type) {
    $field_pattern = 'italiagov_error_page_' . $error_type . '_';
    $container_name = 'error_page_settings_details_' . $error_type;
    $get_setting_pattern = 'error_page_settings.' . $container_name . '.' . $field_pattern;
    // Container of error type.
    $err_settings[$container_name] = [
      '#type' => 'details',
      '#title' => t('Settings about error page: @error_type', ['@error_type' => $error_type]),
      '#open' => FALSE,
      // Error subtitle.
      $field_pattern . 'subtitle' => [
        '#type' => 'textfield',
        '#title' => t('Subtitle'),
        '#description' => t('Insert the subtitle of @error_type error page', ['@error_type' => $error_type]),
        '#default_value' => theme_get_setting($get_setting_pattern . 'subtitle'),
      ],
      // Error body text.
      $field_pattern . 'text' => [
        '#type' => 'textfield',
        '#title' => t('Body text'),
        '#description' => t('Insert the body text of @error_type error page. The placeholder "@return_back" will be replaced with a "returning back link"', ['@error_type' => $error_type]),
        '#default_value' => theme_get_setting($get_setting_pattern . 'text'),
      ],
      // Error body text.
      $field_pattern . 'return_text' => [
        '#type' => 'textfield',
        '#title' => t('Return button text'),
        '#description' => t('Insert the return button text'),
        '#default_value' => theme_get_setting($get_setting_pattern . 'return_text'),
      ],
    ];
  }

  $form['error_page_settings'] = array_merge(
    $form['error_page_settings'],
    $err_settings
  );
}
