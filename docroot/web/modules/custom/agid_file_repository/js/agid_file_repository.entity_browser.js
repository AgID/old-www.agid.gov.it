/**
 * @file agid_file_repository.entity_browser.js
 *
 * Reacts on entities selection automatically opening edit form.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  /**
   * Agid file repository namespace definition.
   *
   * @type {{}}
   */
  Drupal.agid_file_repository = Drupal.agid_file_repository || {};

  /**
   * Agid file repository saved flag.
   *
   * @type {boolean}
   */
  Drupal.agid_file_repository.saved = false;

  /**
   * Agid file repository remove button label.
   *
   * @type {string}
   */
  Drupal.agid_file_repository.remove = undefined;

  /**
   * Reacts on "entities selected" event.
   *
   * @param {object} event
   *   Event object.
   * @param {string} uuid
   *   Entity browser UUID.
   * @param {array} entities
   *   Array of selected entities.
   */
  Drupal.agid_file_repository.selectionCompleted = function (event, uuid, entities) {
    var cardinality = isNaN(parseInt(drupalSettings['entity_browser'][uuid]['cardinality'])) ? -1 : parseInt(drupalSettings['entity_browser'][uuid]['cardinality']);
    if (cardinality != 1) {
      return;
    }

    var selector = drupalSettings['entity_browser'][uuid]['selector'].replace('#', ''),
      field_name = selector.replace('edit-', '').replace('-target-id', '').replace(/-/g, '_') + '[target_id]',
      edit_selector = selector.replace('target-id', 'current-items-0-edit-button'),
      remove_selector = selector.replace('target-id', 'current-items-0-remove-button');

    // On ajax complete, auto trigger mouse down event on the Edit button.
    $(document).ajaxComplete(function (event, xhr, settings) {
      if (settings.extraData === undefined || !settings.extraData.hasOwnProperty('_triggering_element_name')) {
        return;
      }

      var extra = settings.extraData,
        entity_selector = $('[name="' + field_name + '"]').val(),
        $container = $('.item-container[data-entity-id="' + entity_selector + '"]'),
        $edit = $container.find('input[data-drupal-selector="' + edit_selector + '"]'),
        $remove = $container.find('input[data-drupal-selector="' + remove_selector + '"]');

      // Store the remove button label in order to reuse it in the future.
      if ((Drupal.agid_file_repository.remove === undefined) && $remove.length) {
        Drupal.agid_file_repository.remove = $remove.val();
      }

      // Reset the saved flag only if the file has been removed.
      if (extra._triggering_element_value == Drupal.agid_file_repository.remove) {
        Drupal.agid_file_repository.saved = false;
        return;
      }

      // Trigger mouse down event and set the saved flag to TRUE.
      if ((
          (extra._triggering_element_name == 'field_repository_file[target_id]') ||
          (extra._triggering_element_name == 'field_repository_files[target_id]')
        ) && !Drupal.agid_file_repository.saved) {
        event.stopImmediatePropagation();
        Drupal.agid_file_repository.saved = true;
        $edit.trigger('mousedown');
      }
    });
  };

}(jQuery, Drupal, drupalSettings));
